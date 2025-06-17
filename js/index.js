document.addEventListener("DOMContentLoaded", function () {
  // Estado global para contactos (evita redeclarações)
  let termoAtual = "";
  let paginaAtual = 1;
  let totalPaginas = 1;
  const porPagina = 50;
  let debounceTimer = null;

  // -------------------- TABS PRINCIPAIS --------------------
  const tabs = document.querySelectorAll(".tab-link");
  let tabAtual = sessionStorage.getItem("tabAtual") || "tab-visaogeral";

  function ativarTab(tabId) {
    // Esconde todos os tabs principais
    document
      .querySelectorAll('[id^="tab-"]')
      .forEach((t) => t.classList.add("hidden"));
    tabs.forEach((link) => {
      link.classList.remove(
        "text-white",
        "bg-gray-700",
        "dark:bg-gray-700",
        "font-semibold"
      );
      link.classList.add(
        "text-gray-500",
        "dark:text-gray-400",
        "bg-white",
        "dark:bg-gray-800"
      );
    });

    const targetTab = document.getElementById(tabId);
    const linkEl = document.querySelector(`.tab-link[data-tab="${tabId}"]`);
    if (targetTab && linkEl) {
      targetTab.classList.remove("hidden");
      linkEl.classList.remove(
        "text-gray-500",
        "dark:text-gray-400",
        "bg-white",
        "dark:bg-gray-800"
      );
      linkEl.classList.add(
        "text-white",
        "bg-gray-700",
        "dark:bg-gray-700",
        "font-semibold"
      );
    }
    sessionStorage.setItem("tabAtual", tabId);

    // Sempre que entras em contactos, reatribui handlers das subtabs
    if (tabId === "tab-novocontacto") {
      reatribuirListenersSubTabs(); // <- CHAMA ESTA FUNÇÃO LOGO QUE ENTRAS EM CONTACTOS
      ativarSubTab(
        sessionStorage.getItem("contactSubTab") || "tab-todos-contactos"
      );
    }
  }

  // Função que garante que os listeners estão sempre ativos
  function reatribuirListenersSubTabs() {
    document.querySelectorAll(".contact-tab-link").forEach((tab) => {
      tab.onclick = null; // Remove antigos, se existirem
      tab.addEventListener("click", function (e) {
        e.preventDefault();
        ativarSubTab(this.getAttribute("data-contacttab"));
      });
    });
  }

  tabs.forEach((tab) => {
    tab.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("data-tab");
      if (targetId !== "tab-novocontacto")
        sessionStorage.removeItem("contactSubTab");
      ativarTab(targetId);
    });
  });

  // -------------------- TABS SECUNDÁRIAS (Contactos) --------------------
  function ativarSubTab(subTabId) {
    document
      .querySelectorAll(".contact-tab-content")
      .forEach((c) => c.classList.add("hidden"));
    document.querySelectorAll(".contact-tab-link").forEach((l) => {
      l.classList.remove(
        "text-blue-700",
        "bg-blue-50",
        "dark:text-white",
        "dark:bg-gray-700",
        "font-semibold"
      );
      l.classList.add(
        "text-gray-500",
        "bg-white",
        "dark:text-gray-400",
        "dark:bg-gray-800"
      );
    });
    const target = document.getElementById(subTabId);
    const linkEl = document.querySelector(
      `.contact-tab-link[data-contacttab="${subTabId}"]`
    );
    if (target && linkEl) {
      target.classList.remove("hidden");
      linkEl.classList.remove(
        "text-gray-500",
        "bg-white",
        "dark:text-gray-400",
        "dark:bg-gray-800"
      );
      linkEl.classList.add(
        "text-blue-700",
        "bg-blue-50",
        "dark:text-white",
        "dark:bg-gray-700",
        "font-semibold"
      );
    }
    sessionStorage.setItem("contactSubTab", subTabId);
    if (subTabId === "tab-todos-contactos") {
      inicializarPesquisaContactos();
      fetchContactos("", 1);
    } else {
      destruirPesquisaContactos();
    }
  }

  document.querySelectorAll(".contact-tab-link").forEach((tab) => {
    tab.addEventListener("click", function (e) {
      e.preventDefault();
      ativarSubTab(this.getAttribute("data-contacttab"));
    });
  });

  // -------------------- INICIALIZAÇÃO --------------------
  ativarTab(tabAtual);

  // -------------------- CONTACTOS: PESQUISA E PAGINAÇÃO --------------------
  function inicializarPesquisaContactos() {
    const inputPesquisa = document.getElementById("pesquisar-contactos");
    if (!inputPesquisa) return;
    inputPesquisa.oninput = null;
    inputPesquisa.value = "";
    inputPesquisa.addEventListener("input", debounceInputHandler);
  }
  function destruirPesquisaContactos() {
    const inputPesquisa = document.getElementById("pesquisar-contactos");
    if (inputPesquisa)
      inputPesquisa.removeEventListener("input", debounceInputHandler);
  }
  function debounceInputHandler(e) {
    clearTimeout(debounceTimer);
    termoAtual = e.target.value.trim();
    paginaAtual = 1;
    debounceTimer = setTimeout(() => {
      fetchContactos(termoAtual, paginaAtual);
    }, 300);
  }
  function renderPaginacao() {
    const paginacaoDiv = document.getElementById("paginacao-contactos");
    if (!paginacaoDiv) return;
    paginacaoDiv.innerHTML = "";
    if (totalPaginas <= 1) return;
    let html = `<div class="flex gap-6 items-center justify-center">`;
    html += `<button ${
      paginaAtual === 1 ? "disabled" : ""
    } id="pag-anterior" class="cursor-pointer px-4 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">Anterior</button>`;
    html += `<span class="text-white font-semibold">Página ${paginaAtual} de ${totalPaginas}</span>`;
    html += `<button ${
      paginaAtual === totalPaginas ? "disabled" : ""
    } id="pag-seguinte" class="cursor-pointer px-4 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">Seguinte</button>`;
    html += `</div>`;
    paginacaoDiv.innerHTML = html;

    document.getElementById("pag-anterior").onclick = () => {
      if (paginaAtual > 1) {
        paginaAtual--;
        fetchContactos(termoAtual, paginaAtual);
      }
    };
    document.getElementById("pag-seguinte").onclick = () => {
      if (paginaAtual < totalPaginas) {
        paginaAtual++;
        fetchContactos(termoAtual, paginaAtual);
      }
    };
  }
  function fetchContactos(termo = "", pagina = 1) {
    const tbodyContactos = document.getElementById("tabela-contactos");
    if (!tbodyContactos) return;
    termoAtual = termo;
    paginaAtual = pagina;
    fetch(
      `/api/pesquisar_contactos.php?q=${encodeURIComponent(
        termo
      )}&page=${pagina}`
    )
      .then((r) => r.json())
      .then((data) => {
        tbodyContactos.innerHTML = "";
        if (
          !data.registos ||
          !Array.isArray(data.registos) ||
          !data.registos.length
        ) {
          tbodyContactos.innerHTML = `<tr><td colspan="8" class="py-2 px-3 text-center text-gray-400">Sem resultados</td></tr>`;
        } else {
          data.registos.forEach((c) => {
            tbodyContactos.innerHTML += `
            <tr class="border-b border-gray-100 dark:border-gray-700">
              <td class="py-2 px-3">${c.publico_id ?? ""}</td>
              <td class="py-2 px-3">${c.nome ?? ""}</td>
              <td class="py-2 px-3">${c.email ?? ""}</td>
              <td class="py-2 px-3">${c.gestor_nome ?? ""}</td>
              <td class="py-2 px-3">${c.canal_nome ?? ""}</td>
              <td class="py-2 px-3">${c.lista_nome ?? ""}</td>
              <td class="py-2 px-3">${c.data_registo ?? ""}</td>
              <td class="py-2 px-3 text-right"></td>
            </tr>`;
          });
        }
        totalPaginas = Number.isFinite(data.total)
          ? Math.ceil(data.total / porPagina) || 1
          : 1;
        renderPaginacao();
      })
      .catch((error) => {
        tbodyContactos.innerHTML = `<tr><td colspan="8" class="py-2 px-3 text-center text-red-400">Erro ao carregar contactos</td></tr>`;
      });
  }

  // -------------------- MODAIS --------------------
  document.querySelectorAll(".abrir-modal").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const target = btn.dataset.modal;
      const modal = document.getElementById(`modal-${target}`);
      if (modal) {
        modal.classList.remove("hidden");
        modal.querySelector("input[type='text']")?.focus();
      }
    });
  });
  window.addEventListener("click", function (e) {
    if (
      e.target.classList.contains("abrir-modal") ||
      e.target.closest(".abrir-modal") ||
      e.target.classList.contains("fechar-modal") ||
      e.target.closest(".fechar-modal")
    )
      return;
    if (!e.target.closest(".modal-content")) {
      document
        .querySelectorAll(".modal")
        .forEach((m) => m.classList.add("hidden"));
    }
  });

  // -------------------- TOAST ALERTS --------------------
  function mostrarAlerta(mensagem, tipo = "success") {
    const alerta = document.getElementById("alerta-custom");
    const span = document.getElementById("alerta-mensagem");
    alerta.classList.remove("bg-green-600", "bg-red-600");
    alerta.classList.add(tipo === "error" ? "bg-red-600" : "bg-green-600");
    span.textContent = mensagem;
    alerta.classList.remove("hidden", "opacity-0");
    alerta.classList.add("opacity-100");
    setTimeout(() => {
      alerta.classList.remove("opacity-100");
      alerta.classList.add("opacity-0");
      setTimeout(() => alerta.classList.add("hidden"), 300);
    }, 3000);
  }

  // -------------------- LISTAS: CRIAR, EDITAR, APAGAR --------------------
  let listaIdParaApagar = null;
  let linhaParaRemover = null;
  document.getElementById("cancelar-apagar")?.addEventListener("click", () => {
    document.getElementById("modal-confirmar-apagar").classList.add("hidden");
    listaIdParaApagar = null;
    linhaParaRemover = null;
  });
  document.getElementById("confirmar-apagar")?.addEventListener("click", () => {
    if (!listaIdParaApagar) return;
    fetch("", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `action=apagar_lista&lista_id=${encodeURIComponent(
        listaIdParaApagar
      )}`,
    })
      .then((res) => {
        if (!res.ok) throw new Error("Erro ao apagar");
        if (linhaParaRemover) linhaParaRemover.remove();
        mostrarAlerta("Lista apagada com sucesso!");
      })
      .catch(() => mostrarAlerta("Erro ao apagar a lista.", "error"))
      .finally(() => {
        document
          .getElementById("modal-confirmar-apagar")
          .classList.add("hidden");
        listaIdParaApagar = null;
        linhaParaRemover = null;
      });
  });
  document
    .getElementById("form-nova-lista")
    ?.addEventListener("submit", function (e) {
      e.preventDefault();
      const nomeInput = document.getElementById("nova_lista_nome");
      const nome = nomeInput.value.trim();
      if (!nome) return mostrarAlerta("O nome da lista é obrigatório", "error");
      fetch("", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: `action=nova_lista&nova_lista_nome=${encodeURIComponent(nome)}`,
      })
        .then((res) => {
          if (!res.ok) throw new Error("Erro ao criar lista");
          return res.json(); // deve devolver { id: ..., nome: ... }
        })
        .then((data) => {
          const tbody = document.querySelector("#tab-listas tbody");
          if (!tbody) return;
          const novaLinha = document.createElement("tr");
          novaLinha.className = "border-b border-gray-100 dark:border-gray-700";
          novaLinha.innerHTML = `
          <td class="py-2 px-3">${data.nome}</td>
          <td class="py-2 px-3 text-right">
            <select onchange="handleListaAcao(this)" class="select-acao-lista bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-1 cursor-pointer" data-id="${data.id}" data-nome="${data.nome}">
              <option selected disabled>Ações</option>
              <option value="editar">Editar</option>
              <option value="apagar">Apagar</option>
            </select>
          </td>
        `;
          tbody.appendChild(novaLinha);
          document.getElementById("modal-nova-lista").classList.add("hidden");
          nomeInput.value = "";
          mostrarAlerta("Lista criada com sucesso!");
          novaLinha
            .querySelector("select")
            .addEventListener("change", function () {
              handleListaAcao(this);
            });
        })
        .catch(() => {
          mostrarAlerta("Erro ao criar lista.", "error");
        });
    });
  function handleListaAcao(select) {
    const acao = select.value;
    const id = select.dataset.id;
    const nome = select.dataset.nome;
    if (acao === "editar") {
      document.getElementById("editar_lista_id").value = id;
      document.getElementById("editar_lista_nome").value = nome;
      document.getElementById("modal-editar-lista").classList.remove("hidden");
    }
    if (acao === "apagar") {
      listaIdParaApagar = id;
      linhaParaRemover = select.closest("tr");
      document
        .getElementById("modal-confirmar-apagar")
        .classList.remove("hidden");
    }
    select.selectedIndex = 0;
  }
  document.querySelectorAll(".select-acao-lista").forEach((select) => {
    select.addEventListener("change", function () {
      handleListaAcao(this);
    });
  });
  window.handleListaAcao = handleListaAcao;

  // -------------------- IMPORTAÇÃO FICHEIRO --------------------
  const formImportar = document.getElementById("form-importar-ficheiro");
  if (formImportar) {
    formImportar.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(formImportar);
      try {
        const resposta = await fetch(formImportar.action, {
          method: "POST",
          body: formData,
        });
        const resultado = await resposta.json();
        if (resposta.ok && resultado.resultados) {
          const total = resultado.resultados.length;
          const sucesso = resultado.resultados.filter((r) => r.sucesso).length;
          const erro = total - sucesso;
          mostrarAlerta(
            `✅ Importados: ${sucesso}, ❌ Com erro: ${erro}`,
            erro > 0 ? "error" : "success"
          );
          formImportar.reset(); // limpa o input file
        } else {
          throw new Error("Erro de resposta");
        }
      } catch (err) {
        mostrarAlerta(
          "❌ Erro ao importar ficheiro. Verifique o formato.",
          "error"
        );
      }
    });
  }

  //Preview do HTML

  document.getElementById("preview-btn").addEventListener("click", function () {
    var html = document.getElementById("editor").value;
    var previewFrame = document.getElementById("html-preview");
    previewFrame.srcdoc = html;
  });
});

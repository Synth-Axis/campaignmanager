document.addEventListener("DOMContentLoaded", function () {
  // Tabs principais
  const tabs = document.querySelectorAll(".tab-link");
  tabs.forEach((tab) => {
    tab.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("data-tab");
      const targetTab = document.getElementById(targetId);
      if (!targetTab) return;

      // Esconde todos os tabs principais
      document
        .querySelectorAll('[id^="tab-"]')
        .forEach((t) => t.classList.add("hidden"));

      // Remove estilos de ativo das tabs principais
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

      // Mostra o tab selecionado
      targetTab.classList.remove("hidden");
      this.classList.remove(
        "text-gray-500",
        "dark:text-gray-400",
        "bg-white",
        "dark:bg-gray-800"
      );
      this.classList.add(
        "text-white",
        "bg-gray-700",
        "dark:bg-gray-700",
        "font-semibold"
      );

      // Quando muda para tab principal, remove o subtab guardado (reset)
      if (targetId !== "tab-novocontacto") {
        sessionStorage.removeItem("contactSubTab");
      }

      // Guardar tab atual no sessionStorage
      sessionStorage.setItem("tabAtual", targetId);
    });
  });

  // Ativar tab/subtab guardados (sem clique automático!

  // --- Contactos: Pesquisa + Paginação AJAX ---
  const inputPesquisa = document.getElementById("pesquisar-contactos");
  const tbodyContactos = document.getElementById("tabela-contactos");
  let tabGuardada = sessionStorage.getItem("tabAtual");
  const tabInicial =
    tabGuardada ||
    document.querySelector(".tab-link[data-tab]")?.dataset.tab ||
    "tab-visaogeral";
  if (!tabGuardada) sessionStorage.setItem("tabAtual", "tab-visaogeral");

  const subTabGuardado = sessionStorage.getItem("contactSubTab");
  const subTabInicial =
    subTabGuardado ||
    document
      .querySelector(".contact-tab-link[data-contacttab]")
      ?.getAttribute("data-contacttab");

  // Funções para ativar tab e subtab SEM clique
  function ativarTab(tabId) {
    document
      .querySelectorAll('[id^="tab-"]')
      .forEach((t) => t.classList.add("hidden"));
    document.querySelectorAll(".tab-link").forEach((link) => {
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
    const tabEl = document.querySelector(`.tab-link[data-tab="${tabId}"]`);
    if (targetTab && tabEl) {
      targetTab.classList.remove("hidden");
      tabEl.classList.remove(
        "text-gray-500",
        "bg-white",
        "dark:text-gray-400",
        "dark:bg-gray-800"
      );
      tabEl.classList.add(
        "text-white",
        "bg-gray-700",
        "dark:bg-gray-700",
        "font-semibold"
      );
    }
  }

  function ativarSubTab(subTabId) {
    document
      .querySelectorAll(".contact-tab-content")
      .forEach(function (content) {
        content.classList.add("hidden");
      });
    document.querySelectorAll(".contact-tab-link").forEach(function (l) {
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
    const tabEl = document.querySelector(
      `.contact-tab-link[data-contacttab="${subTabId}"]`
    );
    if (target && tabEl) {
      target.classList.remove("hidden");
      tabEl.classList.remove(
        "text-gray-500",
        "bg-white",
        "dark:text-gray-400",
        "dark:bg-gray-800"
      );
      tabEl.classList.add(
        "text-blue-700",
        "bg-blue-50",
        "dark:text-white",
        "dark:bg-gray-700",
        "font-semibold"
      );
    }
  }

  // Ativa só no load inicial
  ativarTab(tabInicial);
  if (tabInicial === "tab-novocontacto" && subTabInicial) {
    ativarSubTab(subTabInicial);

    // Só faz fetchContactos quando ambos estão ativos
    if (
      subTabInicial === "tab-todos-contactos" &&
      inputPesquisa &&
      tbodyContactos
    ) {
      fetchContactos("", 1);
    }
  }

  // Abrir modal
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

  // Fechar modal ao clicar fora do conteúdo
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

  // Toast Alerts
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

  // Lógica de ações
  let listaIdParaApagar = null;
  let linhaParaRemover = null;

  // Confirmação apagar - Modal
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

  // SUBMISSÃO AJAX - Criar Nova Lista
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

          // Adiciona nova linha à tabela
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

          // Fechar modal, limpar input, aplicar handler
          document.getElementById("modal-nova-lista").classList.add("hidden");
          nomeInput.value = "";
          mostrarAlerta("Lista criada com sucesso!");

          // Aplicar event listener ao novo select
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

  // Função de ações (editar/apagar)
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

  // Aplicar handler a cada select
  document.querySelectorAll(".select-acao-lista").forEach((select) => {
    select.addEventListener("change", function () {
      handleListaAcao(this);
    });
  });

  window.handleListaAcao = handleListaAcao;

  // SUBMISSÃO AJAX - Importar Contactos por Ficheiro
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

  // Criar (ou obter) container de paginação
  const porPagina = 50;
  let termoAtual = "";
  let paginaAtual = 1;
  let totalPaginas = 1;
  let debounceTimer = null;

  // Container para paginação
  const paginacaoDiv = document.getElementById("paginacao-contactos");
  if (!paginacaoDiv && tbodyContactos) {
    paginacaoDiv = document.createElement("div");
    paginacaoDiv.id = "paginacao-contactos";
    tbodyContactos.parentNode.appendChild(paginacaoDiv);
  }

  function renderPaginacao() {
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
        // Atualiza o total de páginas
        totalPaginas = Number.isFinite(data.total)
          ? Math.ceil(data.total / porPagina) || 1
          : 1;
        renderPaginacao();
      })
      .catch((error) => {
        tbodyContactos.innerHTML = `<tr><td colspan="8" class="py-2 px-3 text-center text-red-400">Erro ao carregar contactos</td></tr>`;
      });
  }

  // --- Pesquisa (com debounce) ---
  if (inputPesquisa && tbodyContactos) {
    inputPesquisa.addEventListener("input", function () {
      clearTimeout(debounceTimer);
      termoAtual = inputPesquisa.value.trim();
      paginaAtual = 1;
      debounceTimer = setTimeout(() => {
        fetchContactos(termoAtual, paginaAtual);
      }, 300);
    });

    // Carrega primeira página ao iniciar
    fetchContactos("", 1);
  }
});

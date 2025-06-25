document.addEventListener("DOMContentLoaded", function () {
  let termoAtual = "";
  let paginaAtual = 1;
  let totalPaginas = 1;
  const porPagina = 50;
  let debounceTimer = null;

  let entidadeParaApagar = null; // "lista" ou "contacto"
  let idParaApagar = null;
  let linhaParaRemover = null;

  // -------------------- TABS PRINCIPAIS --------------------
  const tabs = document.querySelectorAll(".tab-link");
  let tabAtual = sessionStorage.getItem("tabAtual") || "tab-visaogeral";

  function ativarTab(tabId) {
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
        "bg-white",
        "dark:text-gray-400",
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

    if (tabId === "tab-novocontacto") {
      reatribuirListenersSubTabs();
      ativarSubTab(
        sessionStorage.getItem("contactSubTab") || "tab-todos-contactos"
      );
    }
  }

  function reatribuirListenersSubTabs() {
    document.querySelectorAll(".contact-tab-link").forEach((tab) => {
      tab.onclick = null;
      tab.addEventListener("click", function (e) {
        e.preventDefault();
        ativarSubTab(this.getAttribute("data-contacttab"));
      });
    });
  }

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

  tabs.forEach((tab) => {
    tab.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("data-tab");
      if (targetId !== "tab-novocontacto")
        sessionStorage.removeItem("contactSubTab");
      ativarTab(targetId);
    });
  });

  document.querySelectorAll(".contact-tab-link").forEach((tab) => {
    tab.addEventListener("click", function (e) {
      e.preventDefault();
      ativarSubTab(this.getAttribute("data-contacttab"));
    });
  });

  ativarTab(tabAtual);

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
    debounceTimer = setTimeout(
      () => fetchContactos(termoAtual, paginaAtual),
      50
    );
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
            const row = document.createElement("tr");
            row.className = "border-b border-gray-100 dark:border-gray-700";
            row.innerHTML = `
              <td class="py-2 px-3">
                  <input type="checkbox" class="checkbox-contacto" name="contactosSelecionados[]" value=${
                    c.publico_id ?? ""
                  }>
              </td>
              <td class="py-2 px-3">${c.publico_id ?? ""}</td>
              <td class="py-2 px-3">${c.nome ?? ""}</td>
              <td class="py-2 px-3">${c.email ?? ""}</td>
              <td class="py-2 px-3">${c.gestor_nome ?? ""}</td>
              <td class="py-2 px-3">${c.canal_nome ?? ""}</td>
              <td class="py-2 px-3">${c.lista_nome ?? ""}</td>
              <td class="py-2 px-3">${c.data_registo ?? ""}</td>
              <td class="py-2 px-3 text-right">
                <select class="select-acao-contacto bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-1 cursor-pointer"
                  data-id="${c.publico_id}"
                  data-nome="${c.nome ?? ""}"
                  data-email="${c.email ?? ""}"
                  data-gestor="${c.gestor_id ?? ""}"
                  data-lista="${c.lista_id ?? ""}"
                  data-canal="${c.canal_id ?? ""}">
                  <option selected disabled>Acções</option>
                  <option value="editar">Editar</option>
                  <option value="apagar">Apagar</option>
                </select>
              </td>`;
            tbodyContactos.appendChild(row);
          });

          // Aplicar os listeners após inserir linhas
          tbodyContactos
            .querySelectorAll(".select-acao-contacto")
            .forEach((select) => {
              select.addEventListener("change", function () {
                handleAcaoContacto(this);
              });
            });
        }
        totalPaginas = Number.isFinite(data.total)
          ? Math.ceil(data.total / porPagina) || 1
          : 1;
        renderPaginacao();
      })
      .catch(() => {
        tbodyContactos.innerHTML = `<tr><td colspan="8" class="py-2 px-3 text-center text-red-400">Erro ao carregar contactos</td></tr>`;
      });
  }

  function renderPaginacao() {
    const paginacaoDiv = document.getElementById("paginacao-contactos");
    if (!paginacaoDiv) return;
    paginacaoDiv.innerHTML = "";
    if (totalPaginas <= 1) return;
    paginacaoDiv.innerHTML = `
      <div class="flex gap-6 items-center justify-center">
        <button ${
          paginaAtual === 1 ? "disabled" : ""
        } id="pag-anterior" class="cursor-pointer px-4 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">Anterior</button>
        <span class="text-white font-semibold">Página ${paginaAtual} de ${totalPaginas}</span>
        <button ${
          paginaAtual === totalPaginas ? "disabled" : ""
        } id="pag-seguinte" class="cursor-pointer px-4 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">Seguinte</button>
      </div>`;

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

  // -------------------- MODAIS & AÇÕES --------------------
  window.handleAcaoContacto = function (select) {
    const acao = select.value;
    const id = select.dataset.id;

    if (acao === "editar") {
      fetch(`/api/get_contacto.php?id=${encodeURIComponent(id)}`)
        .then((res) => res.json())
        .then((data) => {
          if (!data || !data.publico_id) {
            alert("Contacto não encontrado.");
            return;
          }

          document.getElementById("editar_contacto_id").value = data.publico_id;
          document.getElementById("editar_nome").value = data.nome || "";
          document.getElementById("editar_email").value = data.email || "";
          document.getElementById("editar_gestor").value = data.gestor_id || "";
          document.getElementById("editar_lista").value = data.lista_id || "";
          document.getElementById("editar_canal").value = data.canal_id || "";

          document
            .getElementById("modal-editar-contacto")
            .classList.remove("hidden");

          // Repor o select para a opção default após usar
          select.value = "Acções";
        })
        .catch(() => {
          alert("Erro ao obter dados do contacto.");
        });
    }

    if (acao === "apagar") {
      entidadeParaApagar = "contacto";
      idParaApagar = id;
      linhaParaRemover = select.closest("tr");
      document.getElementById("texto-confirmacao").textContent =
        "Tem a certeza que deseja apagar este contacto?";
      document
        .getElementById("modal-confirmar-apagar")
        .classList.remove("hidden");
    }
  };

  window.handleListaAcao = function (select) {
    const acao = select.value;
    const id = select.dataset.id;
    const nome = select.dataset.nome;

    if (acao === "editar") {
      document.getElementById("editar_lista_id").value = id;
      document.getElementById("editar_lista_nome").value = nome;
      document.getElementById("modal-editar-lista").classList.remove("hidden");
    }

    if (acao === "apagar") {
      entidadeParaApagar = "lista";
      idParaApagar = id;
      linhaParaRemover = select.closest("tr");
      document.getElementById("texto-confirmacao").textContent =
        "Tem a certeza que deseja apagar esta lista?";
      document
        .getElementById("modal-confirmar-apagar")
        .classList.remove("hidden");
    }

    select.selectedIndex = 0;
  };

  document.getElementById("cancelar-apagar")?.addEventListener("click", () => {
    document.getElementById("modal-confirmar-apagar").classList.add("hidden");
    entidadeParaApagar = null;
    idParaApagar = null;
    linhaParaRemover = null;
  });

  document.getElementById("confirmar-apagar")?.addEventListener("click", () => {
    if (!entidadeParaApagar || !idParaApagar) return;

    const body =
      entidadeParaApagar === "lista"
        ? `action=apagar_lista&lista_id=${encodeURIComponent(idParaApagar)}`
        : `action=apagar_contacto&contacto_id=${encodeURIComponent(
            idParaApagar
          )}`;

    fetch("", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: body,
    })
      .then((res) => {
        if (!res.ok) throw new Error("Erro ao apagar");
        if (linhaParaRemover && entidadeParaApagar === "lista")
          linhaParaRemover.remove();
        if (entidadeParaApagar === "contacto")
          fetchContactos(termoAtual, paginaAtual);
        mostrarAlerta(
          `${
            entidadeParaApagar === "lista" ? "Lista" : "Contacto"
          } apagado com sucesso!`
        );
      })
      .catch(() =>
        mostrarAlerta(`Erro ao apagar ${entidadeParaApagar}.`, "error")
      )
      .finally(() => {
        document
          .getElementById("modal-confirmar-apagar")
          .classList.add("hidden");
        entidadeParaApagar = null;
        idParaApagar = null;
        linhaParaRemover = null;
        select.selectedIndex = 0;
      });
  });

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
          formImportar.reset();
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

  // -------------------- GRÁFICO DE CONTACTOS --------------------
  const selectPeriodo = document.getElementById("filtro-periodo");
  const graficoCanvas = document.getElementById("grafico-crescimento");
  let chartInstance;

  function desenharGrafico(dados) {
    const labels = dados.map((d) => d.dia);
    const valores = dados.map((d) => d.total);

    if (chartInstance) chartInstance.destroy();

    const ctx = graficoCanvas.getContext("2d");
    chartInstance = new Chart(ctx, {
      type: "line",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Novos Contactos",
            data: valores,
            fill: false,
            borderColor: "rgb(59, 130, 246)",
            tension: 0.1,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
        },
        scales: {
          y: { beginAtZero: true },
        },
      },
    });
  }

  function carregarGrafico(periodoLabel) {
    fetch(
      `/api/crescimento_contactos.php?periodo=${encodeURIComponent(
        periodoLabel
      )}`
    )
      .then((res) => res.json())
      .then((data) => desenharGrafico(data))
      .catch((err) => console.error("Erro ao buscar dados do gráfico:", err));
  }

  if (selectPeriodo && graficoCanvas) {
    carregarGrafico(selectPeriodo.value);

    selectPeriodo.addEventListener("change", function () {
      carregarGrafico(this.value);
    });
  }

  // Permite fechar modais ao clicar fora do conteúdo
  document.querySelectorAll(".modal").forEach((modal) => {
    modal.addEventListener("click", function (e) {
      const conteudo = modal.querySelector(".modal-content");
      if (!conteudo.contains(e.target)) {
        modal.classList.add("hidden");
      }
    });
  });

  document
    .getElementById("form-editar-contacto")
    ?.addEventListener("submit", function (e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);

      fetch("", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            document
              .getElementById("modal-editar-contacto")
              .classList.add("hidden");
            fetchContactos(termoAtual, paginaAtual);
            mostrarAlerta("✅ Contacto atualizado com sucesso!");
          } else {
            mostrarAlerta("❌ Erro ao atualizar o contacto.", "error");
          }
        })
        .catch(() => {
          mostrarAlerta("❌ Erro na comunicação com o servidor.", "error");
        });
    });

  // Selecionar todos os contactos
  document
    .getElementById("selecionar-todos")
    .addEventListener("change", function () {
      document
        .querySelectorAll(".checkbox-contacto")
        .forEach((cb) => (cb.checked = this.checked));
    });

  // Exportar contactos
  document
    .getElementById("selecionar-todos")
    ?.addEventListener("change", function () {
      document
        .querySelectorAll(".checkbox-contacto")
        .forEach((cb) => (cb.checked = this.checked));
    });

  document
    .getElementById("form-exportar-contactos")
    ?.addEventListener("submit", function (e) {
      const selecionados = document.querySelectorAll(
        ".checkbox-contacto:checked"
      );
      const hidden = document.getElementById("exportar-todos");
      hidden.value = selecionados.length === 0 ? "1" : "0";

      selecionados.forEach((cb) => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "contactosSelecionados[]";
        input.value = cb.value;
        this.appendChild(input);
      });
    });
});

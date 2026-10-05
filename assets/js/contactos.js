document.addEventListener("DOMContentLoaded", function () {
  let termoAtual = "";
  let paginaAtual = 1;
  let totalPaginas = 1;
  const porPagina = 50;
  let debounceTimer = null;
  let pedidoContactos = null;
  let totalRegistos = 0;
  const idsSelecionados = new Set();

  let entidadeParaApagar = null;
  let idParaApagar = null;
  let linhaParaRemover = null;

  const tabs = document.querySelectorAll(".tab-link");
  let tabAtual = sessionStorage.getItem("tabAtual") || "tab-visaogeral";

  // helpers para classes
  const inactiveTab = ["bg-surface", "text-neutral"];
  const activeTab = ["bg-primary", "text-white", "font-semibold"];

  function ativarTab(tabId) {
    document
      .querySelectorAll('[id^="tab-"]')
      .forEach((t) => t.classList.add("hidden"));

    // reset tabs
    tabs.forEach((link) => {
      link.classList.remove(...activeTab);
      link.classList.add(...inactiveTab);
    });

    // ativa a tab clicada
    const targetTab = document.getElementById(tabId);
    const linkEl = document.querySelector(`.tab-link[data-tab="${tabId}"]`);
    if (targetTab && linkEl) {
      targetTab.classList.remove("hidden");
      linkEl.classList.remove(...inactiveTab);
      linkEl.classList.add(...activeTab);
    }

    sessionStorage.setItem("tabAtual", tabId);

    // gerir subtabs quando necessário
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
      tab.onclick = function (e) {
        e.preventDefault();
        ativarSubTab(this.getAttribute("data-contacttab"));
      };
    });
  }

  function ativarSubTab(subTabId) {
    const inactiveSubTab = ["bg-surface", "text-neutral"];
    const activeSubTab = ["bg-primary", "text-white", "font-semibold"];

    document
      .querySelectorAll(".contact-tab-content")
      .forEach((c) => c.classList.add("hidden"));
    document.querySelectorAll(".contact-tab-link").forEach((l) => {
      l.classList.remove(...activeSubTab);
      l.classList.add(...inactiveSubTab);
    });

    const target = document.getElementById(subTabId);
    const linkEl = document.querySelector(
      `.contact-tab-link[data-contacttab="${subTabId}"]`
    );
    if (target && linkEl) {
      target.classList.remove("hidden");
      linkEl.classList.remove(...inactiveSubTab);
      linkEl.classList.add(...activeSubTab);
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

  ativarTab(tabAtual);

  // === NOVO: Abertura genérica de modais (.abrir-modal) ===
  // Suporta data-modal="nova-lista" (procura #modal-nova-lista) ou data-modal="modal-nova-lista" (procura diretamente esse id)
  document.querySelectorAll(".abrir-modal").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const target = this.dataset.modal; // ex.: "nova-lista" ou "modal-nova-lista"
      const modal =
        document.getElementById(`modal-${target}`) ||
        document.getElementById(target);
      if (modal) {
        modal.classList.remove("hidden");
      } else {
        console.warn(`Modal não encontrado para: ${target}`);
      }
    });
  });
  // === FIM DO BLOCO NOVO ===

  // --- Pesquisa contactos ---
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
      200
    );
  }

  function escaparHTML(valor) {
    return String(valor ?? "").replace(/[&<>"']/g, (caracter) => ({
      "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;",
    })[caracter]);
  }

  function formatarDataContacto(valor) {
    const partes = String(valor ?? "").match(/^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2}):\d{2}$/);
    if (!partes) return escaparHTML(valor || "—");
    return `<time datetime="${escaparHTML(String(valor).replace(" ", "T"))}">${partes[3]}/${partes[2]}/${partes[1]}<span>${partes[4]}:${partes[5]}</span></time>`;
  }

  function atualizarSelecaoContactos() {
    const checkboxes = document.querySelectorAll("#tabela-contactos .checkbox-contacto");
    let selecionadosNaPagina = 0;
    checkboxes.forEach((checkbox) => {
      checkbox.checked = idsSelecionados.has(checkbox.value);
      checkbox.closest("tr").classList.toggle("contact-row-selected", checkbox.checked);
      if (checkbox.checked) selecionadosNaPagina++;
    });
    const todos = document.getElementById("selecionar-todos");
    todos.checked = checkboxes.length > 0 && selecionadosNaPagina === checkboxes.length;
    todos.indeterminate = selecionadosNaPagina > 0 && selecionadosNaPagina < checkboxes.length;
    todos.disabled = checkboxes.length === 0;
    const quantidade = idsSelecionados.size;
    document.getElementById("estado-selecao-contactos").textContent = quantidade
      ? `${quantidade} ${quantidade === 1 ? "contacto selecionado" : "contactos selecionados"}`
      : "Seleciona contactos para exportar.";
    document.getElementById("limpar-selecao-contactos").classList.toggle("hidden", quantidade === 0);
    document.getElementById("btn-exportar-contactos").textContent = quantidade
      ? `Exportar ${quantidade} ${quantidade === 1 ? "selecionado" : "selecionados"}`
      : "Exportar todos";
  }

  async function fetchContactos(termo = "", pagina = 1) {
    const tbodyContactos = document.getElementById("tabela-contactos");
    if (!tbodyContactos) return;
    termoAtual = termo;
    paginaAtual = pagina;
    pedidoContactos?.abort();
    const pedidoAtual = new AbortController();
    pedidoContactos = pedidoAtual;
    tbodyContactos.setAttribute("aria-busy", "true");
    tbodyContactos.innerHTML = '<tr><td colspan="8" class="contact-table-message">A carregar contactos…</td></tr>';
    document.getElementById("intervalo-contactos").textContent = "A carregar contactos…";
    document.querySelectorAll("#paginacao-contactos button").forEach((botao) => { botao.disabled = true; });
    atualizarSelecaoContactos();

    try {
      const resposta = await fetch(`/api/pesquisar_contactos.php?q=${encodeURIComponent(termo)}&page=${pagina}`, { signal: pedidoAtual.signal });
      if (!resposta.ok) throw new Error("Erro ao carregar contactos");
      const data = await resposta.json();
      if (pedidoAtual !== pedidoContactos) return;
      totalRegistos = Number.isFinite(data.total) ? data.total : 0;
      totalPaginas = Math.max(1, Math.ceil(totalRegistos / porPagina));
      if (paginaAtual > totalPaginas) {
        fetchContactos(termo, totalPaginas);
        return;
      }
      const registos = Array.isArray(data.registos) ? data.registos : [];
      tbodyContactos.replaceChildren();
      if (!registos.length) {
        const mensagem = termo ? "Nenhum contacto corresponde à pesquisa." : "Ainda não existem contactos. Adicione ou importe o primeiro.";
        tbodyContactos.innerHTML = `<tr><td colspan="8" class="contact-table-message">${mensagem}</td></tr>`;
      } else {
        registos.forEach((c) => {
          const row = document.createElement("tr");
          const nome = escaparHTML(c.nome);
          const id = escaparHTML(c.publico_id);
          row.innerHTML = `
            <td class="text-center"><input type="checkbox" class="checkbox-contacto" value="${id}" aria-label="Selecionar ${nome}"></td>
            <td><span class="contact-name">${nome}</span></td>
            <td class="contact-email">${escaparHTML(c.email)}</td>
            <td>${escaparHTML(c.gestor_nome ?? "—")}</td>
            <td><span class="contact-tag" title="${escaparHTML(c.canal_nome)}">${escaparHTML(c.canal_nome ?? "—")}</span></td>
            <td><span class="contact-tag contact-tag-list" title="${escaparHTML(c.lista_nome)}">${escaparHTML(c.lista_nome ?? "—")}</span></td>
            <td class="contact-date">${formatarDataContacto(c.data_registo)}</td>
            <td class="text-center"><button type="button" class="row-action-trigger" data-row-actions="contacto" data-id="${id}" data-nome="${nome}" aria-label="Acções para ${nome}" title="Acções para ${nome}" aria-haspopup="menu" aria-expanded="false" aria-controls="row-actions-menu"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="5" cy="12" r="1.6" /><circle cx="12" cy="12" r="1.6" /><circle cx="19" cy="12" r="1.6" /></svg></button></td>`;
          tbodyContactos.appendChild(row);
        });
      }
      const numero = totalRegistos.toLocaleString("pt-PT");
      document.getElementById("total-contactos").textContent = `${numero} ${termo ? (totalRegistos === 1 ? "resultado" : "resultados") : (totalRegistos === 1 ? "contacto" : "contactos")}`;
      const inicio = registos.length ? (paginaAtual - 1) * porPagina + 1 : 0;
      document.getElementById("intervalo-contactos").textContent = totalRegistos
        ? `A mostrar ${inicio}–${inicio + registos.length - 1} de ${numero}`
        : "0 contactos";
      atualizarSelecaoContactos();
      renderPaginacao();
    } catch (erro) {
      if (erro.name !== "AbortError" && pedidoAtual === pedidoContactos) {
        tbodyContactos.innerHTML = '<tr><td colspan="8" class="contact-table-message"><p class="text-negative">Não foi possível carregar os contactos. Tente pesquisar novamente.</p></td></tr>';
        document.getElementById("intervalo-contactos").textContent = "Contactos indisponíveis";
        document.getElementById("paginacao-contactos").replaceChildren();
        atualizarSelecaoContactos();
      }
    } finally {
      if (pedidoAtual === pedidoContactos) tbodyContactos.setAttribute("aria-busy", "false");
    }
  }

  function renderPaginacao() {
    const paginacaoDiv = document.getElementById("paginacao-contactos");
    if (!paginacaoDiv) return;
    paginacaoDiv.innerHTML = "";
    if (totalRegistos === 0) return;

    paginacaoDiv.innerHTML = `
      <div class="flex gap-3 items-center justify-center text-sm">
        <button type="button" ${paginaAtual === 1 ? "disabled" : ""} id="pag-anterior"
          class="min-h-11 px-3 py-2 rounded-lg bg-field text-ink border border-line hover:bg-highlight/20 disabled:opacity-50 disabled:cursor-not-allowed">Anterior</button>
        <span class="text-neutral whitespace-nowrap">${paginaAtual} / ${totalPaginas}</span>
        <button ${
          paginaAtual === totalPaginas ? "disabled" : ""
        } type="button" id="pag-seguinte"
          class="min-h-11 px-3 py-2 rounded-lg bg-field text-ink border border-line hover:bg-highlight/20 disabled:opacity-50 disabled:cursor-not-allowed">Seguinte</button>
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

  // Alert/Toast com paleta
  function mostrarAlerta(mensagem, tipo = "success") {
    const alerta = document.getElementById("alerta-custom");
    const span = document.getElementById("alerta-mensagem");
    alerta.classList.remove("bg-success", "bg-danger");
    alerta.classList.add(tipo === "error" ? "bg-danger" : "bg-success");
    span.textContent = mensagem;
    alerta.classList.remove("hidden", "opacity-0");
    alerta.classList.add("opacity-100");
    setTimeout(() => {
      alerta.classList.remove("opacity-100");
      alerta.classList.add("opacity-0");
      setTimeout(() => alerta.classList.add("hidden"), 300);
    }, 3000);
  }

  // Ações de contacto
  window.handleAcaoContacto = function (controle, acao) {
    const id = controle.dataset.id;

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
          document.getElementById("editar_nome").focus();
        })
        .catch(() => {
          alert("Erro ao obter dados do contacto.");
        });
    }

    if (acao === "apagar") {
      entidadeParaApagar = "contacto";
      idParaApagar = id;
      linhaParaRemover = controle.closest("tr");
      document.getElementById("texto-confirmacao").textContent =
        "Tem a certeza que deseja apagar este contacto?";
      document
        .getElementById("modal-confirmar-apagar")
        .classList.remove("hidden");
      document.getElementById("cancelar-apagar").focus();
    }
  };

  // Ações de lista
  window.handleListaAcao = function (controle, acao) {
    const id = controle.dataset.id;
    const nome = controle.dataset.nome;

    if (acao === "editar") {
      document.getElementById("editar_lista_id").value = id;
      document.getElementById("editar_lista_nome").value = nome;
      document.getElementById("modal-editar-lista").classList.remove("hidden");
      document.getElementById("editar_lista_nome").focus();
    }

    if (acao === "apagar") {
      entidadeParaApagar = "lista";
      idParaApagar = id;
      linhaParaRemover = controle.closest("tr");
      document.getElementById("texto-confirmacao").textContent =
        "Tem a certeza que deseja apagar esta lista?";
      document
        .getElementById("modal-confirmar-apagar")
        .classList.remove("hidden");
      document.getElementById("cancelar-apagar").focus();
    }

  };

  document.addEventListener("rowaction", (evento) => {
    const { trigger, action } = evento.detail;
    if (trigger.dataset.rowActions === "contacto") handleAcaoContacto(trigger, action);
    else if (trigger.dataset.rowActions === "lista") handleListaAcao(trigger, action);
  });

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
        if (entidadeParaApagar === "contacto") {
          idsSelecionados.delete(String(idParaApagar));
          fetchContactos(termoAtual, paginaAtual);
        }
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
      });
  });

  // Importar ficheiro
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

  // Chart
  const selectPeriodo = document.getElementById("filtro-periodo");
  const graficoCanvas = document.getElementById("grafico-crescimento");
  let chartInstance;

  function aplicarTemaGrafico() {
    if (!chartInstance) return;
    const colors = getComputedStyle(document.documentElement);
    const muted = `rgb(${colors.getPropertyValue("--color-muted").trim()})`;
    const line = `rgb(${colors.getPropertyValue("--color-line").trim()} / .6)`;
    const accent = `rgb(${colors.getPropertyValue("--color-accent").trim()})`;
    chartInstance.data.datasets[0].borderColor = accent;
    chartInstance.data.datasets[0].backgroundColor = accent;
    for (const axis of ["x", "y"]) {
      chartInstance.options.scales[axis].ticks.color = muted;
      chartInstance.options.scales[axis].grid.color = line;
    }
    chartInstance.update("none");
  }

  window.addEventListener("themechange", aplicarTemaGrafico);

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
            borderColor: "#00AEEF", // primary
            backgroundColor: "#00AEEF",
            tension: 0.25,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { ticks: {}, grid: {} },
          y: { beginAtZero: true, ticks: {}, grid: {} },
        },
      },
    });
    aplicarTemaGrafico();
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

  // Modais: fechar ao clicar fora
  document.querySelectorAll(".modal").forEach((modal) => {
    modal.addEventListener("click", function (e) {
      const conteudo = modal.querySelector(".modal-content");
      if (!conteudo.contains(e.target)) modal.classList.add("hidden");
    });
  });

  // Editar contacto (submit)
  document
    .getElementById("form-editar-contacto")
    ?.addEventListener("submit", function (e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);

      fetch("", { method: "POST", body: formData })
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

  // Selecionar todos
  document
    .getElementById("selecionar-todos")
    ?.addEventListener("change", function () {
      document
        .querySelectorAll(".checkbox-contacto")
        .forEach((cb) => {
          if (this.checked) idsSelecionados.add(cb.value);
          else idsSelecionados.delete(cb.value);
        });
      atualizarSelecaoContactos();
    });

  document.getElementById("tabela-contactos")?.addEventListener("change", (evento) => {
    if (!evento.target.matches(".checkbox-contacto")) return;
    const checkbox = evento.target;
    if (checkbox.checked) idsSelecionados.add(checkbox.value);
    else idsSelecionados.delete(checkbox.value);
    atualizarSelecaoContactos();
  });
  document.getElementById("limpar-selecao-contactos")?.addEventListener("click", () => {
    idsSelecionados.clear();
    atualizarSelecaoContactos();
  });
  document.getElementById("toggle-exportar-contactos")?.addEventListener("click", function () {
    const formulario = document.getElementById("form-exportar-contactos");
    const abrir = formulario.classList.contains("hidden");
    formulario.classList.toggle("hidden", !abrir);
    this.setAttribute("aria-expanded", String(abrir));
  });

  // Exportar: enviar selecionados ou todos
  document
    .getElementById("form-exportar-contactos")
    ?.addEventListener("submit", function (evento) {
      if (!this.querySelector('input[name="campos[]"]:checked')) {
        evento.preventDefault();
        mostrarAlerta("Escolha pelo menos um campo para exportar.", "error");
        return;
      }
      this.querySelectorAll('input[name="contactosSelecionados[]"]').forEach((input) => input.remove());
      const hidden = document.getElementById("exportar-todos");
      hidden.value = idsSelecionados.size === 0 ? "1" : "0";

      idsSelecionados.forEach((id) => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "contactosSelecionados[]";
        input.value = id;
        this.appendChild(input);
      });
    });
});

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

  // Ativar tab guardada, ou Visão Geral por padrão
  let tabGuardada = sessionStorage.getItem("tabAtual");
  const tabInicial =
    tabGuardada ||
    document.querySelector(".tab-link[data-tab]")?.dataset.tab ||
    "tab-visaogeral";
  // Se não existir tabGuardada, guarda "tab-visaogeral" como padrão
  if (!tabGuardada) sessionStorage.setItem("tabAtual", "tab-visaogeral");

  if (tabInicial) {
    document.querySelector(`.tab-link[data-tab="${tabInicial}"]`)?.click();
  }

  // Tabs secundários de Contactos
  document.querySelectorAll(".contact-tab-link").forEach(function (tab) {
    tab.addEventListener("click", function (e) {
      e.preventDefault();

      // Desativa todos
      document.querySelectorAll(".contact-tab-link").forEach(function (l) {
        l.classList.remove(
          "text-blue-700",
          "bg-blue-50",
          "dark:text-white",
          "dark:bg-gray-700",
          "font-semibold"
        );
        // Garante que tiramos cores antigas mesmo se estavam em hover
        l.classList.add(
          "text-gray-500",
          "bg-white",
          "dark:text-gray-400",
          "dark:bg-gray-800"
        );
      });

      // Ativa este (igual ao hover)
      this.classList.remove(
        "text-gray-500",
        "bg-white",
        "dark:text-gray-400",
        "dark:bg-gray-800"
      );
      this.classList.add(
        "text-blue-700",
        "bg-blue-50",
        "dark:text-white",
        "dark:bg-gray-700",
        "font-semibold"
      );

      // Esconde todos os conteúdos de contactos
      document
        .querySelectorAll(".contact-tab-content")
        .forEach(function (content) {
          content.classList.add("hidden");
        });

      // Mostra o conteúdo do subtab selecionado
      const target = document.getElementById(
        this.getAttribute("data-contacttab")
      );
      if (target) target.classList.remove("hidden");

      // Guarda o subtab ativo no sessionStorage
      sessionStorage.setItem(
        "contactSubTab",
        this.getAttribute("data-contacttab")
      );
    });
  });

  // Ativar subtab guardado ou o primeiro por defeito
  const subTabGuardado = sessionStorage.getItem("contactSubTab");
  const subTabInicial =
    subTabGuardado ||
    document
      .querySelector(".contact-tab-link[data-contacttab]")
      ?.getAttribute("data-contacttab");
  if (subTabInicial) {
    document
      .querySelector(`.contact-tab-link[data-contacttab="${subTabInicial}"]`)
      ?.click();
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
});

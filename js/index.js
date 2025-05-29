document.addEventListener("DOMContentLoaded", function () {
  // Tabs
  const tabs = document.querySelectorAll(".tab-link");
  tabs.forEach((tab) => {
    tab.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("data-tab");
      const targetTab = document.getElementById(targetId);
      if (!targetTab) return;

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
    });
  });

  // Ativar primeira tab visível
  const firstTab = Array.from(
    document.querySelectorAll(".tab-link[data-tab]")
  ).find((tab) => document.getElementById(tab.dataset.tab));
  if (firstTab) firstTab.click();

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

  // Fechar modal ao clicar fora da .modal-content
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

  // Combobox de ações
  document.querySelectorAll(".select-acao-lista").forEach((select) => {
    select.addEventListener("change", function () {
      const acao = this.value;
      const id = this.dataset.id;

      if (acao === "editar") {
        window.location.href = `/editar-lista?id=${id}`;
      } else if (acao === "apagar") {
        if (confirm("Tem a certeza que deseja apagar esta lista?")) {
          window.location.href = `/apagar-lista?id=${id}`;
        }
      }

      this.selectedIndex = 0;
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  // Tabs
  const tabs = document.querySelectorAll(".tab-link");

  tabs.forEach((tab) => {
    tab.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("data-tab");

      document.querySelectorAll('[id^="tab-"]').forEach((tabContent) => {
        tabContent.classList.add("hidden");
      });

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

      document.getElementById(targetId).classList.remove("hidden");

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

  const firstTab = document.querySelector(
    '.tab-link[data-tab="tab-visaogeral"]'
  );
  if (firstTab) {
    firstTab.click();
  }

  // Modal abrir
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

  // Fechar modal ao clicar fora (opcional)
  window.addEventListener("click", function (e) {
    if (
      e.target.classList.contains("abrir-modal") ||
      e.target.closest(".abrir-modal")
    )
      return;
    if (
      !e.target.closest(".modal-content") &&
      !e.target.closest(".abrir-modal")
    ) {
      document
        .querySelectorAll(".modal")
        .forEach((m) => m.classList.add("hidden"));
    }
  });

  // Dropdown
  document.querySelectorAll(".dropdown-toggle").forEach((btn) => {
    btn.addEventListener("click", function (event) {
      event.stopPropagation();
      const menu = btn.nextElementSibling;
      document.querySelectorAll(".dropdown-menu").forEach((m) => {
        if (m !== menu) m.classList.add("hidden");
      });
      menu.classList.toggle("hidden");
    });
  });

  window.addEventListener("click", function () {
    document
      .querySelectorAll(".dropdown-menu")
      .forEach((m) => m.classList.add("hidden"));
  });
});

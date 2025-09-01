document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("preview-btn").addEventListener("click", function () {
    var html = document.getElementById("editor").value;
    var previewFrame = document.getElementById("html-preview");
    previewFrame.srcdoc = html;
  });

  const tabLinks = document.querySelectorAll(".tab-link");
  const tabContents = document.querySelectorAll(".tab-content");

  tabLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      const targetTab = this.getAttribute("data-tab");

      // reset todas as tabs
      tabLinks.forEach((l) => {
        l.classList.remove("bg-primary", "text-white");
        l.classList.add("bg-white", "text-neutral");
      });

      // ativa a tab clicada
      this.classList.remove("bg-white", "text-neutral");
      this.classList.add("bg-primary", "text-white");

      // mostra a section correspondente
      tabContents.forEach((section) => {
        if (section.id === targetTab) {
          section.classList.remove("hidden");
        } else {
          section.classList.add("hidden");
        }
      });
    });
  });

  const previewBtn = document.getElementById("preview-btn");
  if (previewBtn) {
    previewBtn.addEventListener("click", function () {
      const html = document.getElementById("editor").value;
      const previewFrame = document.getElementById("html-preview");
      previewFrame.srcdoc = html;
    });
  }
});

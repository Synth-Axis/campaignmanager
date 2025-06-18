document.addEventListener("DOMContentLoaded", function () {
  //Preview do HTML
  document.getElementById("preview-btn").addEventListener("click", function () {
    var html = document.getElementById("editor").value;
    var previewFrame = document.getElementById("html-preview");
    previewFrame.srcdoc = html;
  });

  // Tab switching
  const tabLinks = document.querySelectorAll(".tab-link");
  const tabContents = document.querySelectorAll(".tab-content");

  tabLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      const targetTab = this.getAttribute("data-tab");

      // Ativa a tab clicada
      tabLinks.forEach((l) =>
        l.classList.remove(
          "text-blue-700",
          "bg-blue-50",
          "dark:text-white",
          "dark:bg-gray-700"
        )
      );
      this.classList.add(
        "text-blue-700",
        "bg-blue-50",
        "dark:text-white",
        "dark:bg-gray-700"
      );

      // Mostra apenas a section correspondente
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

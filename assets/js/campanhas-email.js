document.addEventListener("DOMContentLoaded", function () {
  const tabList = document.querySelector(".campaign-tabs");
  const tabLinks = tabList ? [...tabList.querySelectorAll('[role="tab"]')] : [];

  function activateTab(selectedTab) {
    tabLinks.forEach((tab) => {
      const selected = tab === selectedTab;
      tab.setAttribute("aria-selected", String(selected));
      tab.tabIndex = selected ? 0 : -1;
      const panel = document.getElementById(tab.getAttribute("aria-controls"));
      if (panel) {
        panel.hidden = !selected;
        panel.classList.toggle("hidden", !selected);
      }
    });
  }

  tabLinks.forEach((tab, index) => {
    tab.addEventListener("click", function (event) {
      event.preventDefault();
      activateTab(tab);
    });

    tab.addEventListener("keydown", function (event) {
      let nextIndex;
      if (event.key === "ArrowRight") nextIndex = (index + 1) % tabLinks.length;
      else if (event.key === "ArrowLeft") nextIndex = (index - 1 + tabLinks.length) % tabLinks.length;
      else if (event.key === "Home") nextIndex = 0;
      else if (event.key === "End") nextIndex = tabLinks.length - 1;
      else return;
      event.preventDefault();
      activateTab(tabLinks[nextIndex]);
      tabLinks[nextIndex].focus();
    });
  });

  if (tabLinks.length) {
    activateTab(tabLinks.find((tab) => tab.getAttribute("aria-selected") === "true") || tabLinks[0]);
  }

  const previewButton = document.getElementById("preview-btn");
  const editor = document.getElementById("editor");
  const previewFrame = document.getElementById("html-preview");
  if (previewButton && editor && previewFrame) {
    previewButton.addEventListener("click", function () {
      previewFrame.srcdoc = editor.value;
    });
  }
});

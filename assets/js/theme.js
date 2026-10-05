(() => {
  const root = document.documentElement;
  const storageKey = "lynxapp-theme";
  const systemTheme = window.matchMedia("(prefers-color-scheme: dark)");
  const desktop = window.matchMedia("(min-width: 1280px)");
  const validPreference = (value) => value === "light" || value === "dark" ? value : null;
  let preference = null;

  try {
    preference = validPreference(localStorage.getItem(storageKey));
  } catch (_) {
    // The toggle also works when browser storage is unavailable.
  }

  function applyTheme(theme) {
    const dark = theme === "dark";
    root.classList.toggle("dark", dark);
    root.dataset.theme = theme;
    document.querySelectorAll("[data-theme-toggle]").forEach((button) => {
      button.setAttribute("aria-checked", String(dark));
      button.title = dark ? "Ativar modo claro" : "Ativar modo escuro";
      button.querySelector("[data-theme-label]").textContent = dark ? "Escuro" : "Claro";
    });
    const themeColor = document.getElementById("app-theme-color");
    if (themeColor) themeColor.content = dark ? "#0B1B24" : "#F5F7FA";
    window.dispatchEvent(new CustomEvent("themechange", { detail: { theme } }));
  }

  const resolvedTheme = () => preference || (systemTheme.matches ? "dark" : "light");
  applyTheme(resolvedTheme());

  systemTheme.addEventListener("change", () => {
    if (!preference) applyTheme(resolvedTheme());
  });

  window.addEventListener("storage", (event) => {
    if (event.key === storageKey || event.key === null) {
      preference = validPreference(event.newValue);
      applyTheme(resolvedTheme());
    }
  });

  document.addEventListener("DOMContentLoaded", () => {
    applyTheme(resolvedTheme());
    document.querySelectorAll("[data-theme-toggle]").forEach((button) => {
      button.addEventListener("click", () => {
        preference = root.classList.contains("dark") ? "light" : "dark";
        try {
          localStorage.setItem(storageKey, preference);
        } catch (_) {
          // Keep the selected theme for this page even without storage.
        }
        applyTheme(preference);
      });
    });

    const navigationToggle = document.querySelector("[data-navigation-toggle]");
    const backdrop = document.querySelector("[data-navigation-backdrop]");
    const sidebar = document.getElementById("drawer-navigation");
    if (!navigationToggle || !backdrop || !sidebar) return;

    function setNavigation(open, restoreFocus = false) {
      document.body.classList.toggle("navigation-open", open);
      navigationToggle.setAttribute("aria-expanded", String(open));
      backdrop.hidden = !open;
      sidebar.inert = !desktop.matches && !open;
      if (open) sidebar.querySelector("a")?.focus();
      if (restoreFocus) navigationToggle.focus();
    }

    navigationToggle.addEventListener("click", () => {
      setNavigation(!document.body.classList.contains("navigation-open"));
    });
    backdrop.addEventListener("click", () => setNavigation(false, true));
    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && document.body.classList.contains("navigation-open")) {
        setNavigation(false, true);
      }
    });
    desktop.addEventListener("change", () => setNavigation(false));
    const currentPath = location.pathname.replace(/\/$/, "") || "/";
    sidebar.querySelectorAll("a[href]").forEach((link) => {
      if (link.getAttribute("href") === "#") return;
      if (new URL(link.href).pathname === currentPath) link.setAttribute("aria-current", "page");
      link.addEventListener("click", () => setNavigation(false));
    });
    setNavigation(false);
  });
})();

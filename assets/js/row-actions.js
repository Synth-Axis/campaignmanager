document.addEventListener("DOMContentLoaded", () => {
  const icons = {
    editar: '<path d="m15 5 4 4M4 20l4-1L20 7a2.8 2.8 0 0 0-4-4L4 15z" />',
    apagar: '<path d="M3 6h18M9 6V4h6v2M5 6l1 14h12l1-14M10 10v6m4-6v6" />',
  };
  const menu = document.createElement("div");
  menu.id = "row-actions-menu";
  menu.className = "row-action-menu";
  menu.setAttribute("role", "menu");
  menu.hidden = true;
  document.body.appendChild(menu);
  let trigger = null;

  function closeMenu(restoreFocus = false) {
    const previous = trigger;
    menu.hidden = true;
    trigger = null;
    previous?.setAttribute("aria-expanded", "false");
    if (restoreFocus && previous?.isConnected) previous.focus({ preventScroll: true });
  }

  function openMenu(button, focusLast = false) {
    closeMenu();
    trigger = button;
    button.setAttribute("aria-expanded", "true");
    menu.setAttribute("aria-label", button.getAttribute("aria-label"));
    menu.replaceChildren();
    for (const [action, label] of [["editar", "Editar"], ["apagar", "Apagar"]]) {
      const item = document.createElement("button");
      item.type = "button";
      item.className = "row-action-item" + (action === "apagar" ? " row-action-item-danger" : "");
      item.setAttribute("role", "menuitem");
      item.dataset.action = action;
      item.innerHTML = `<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${icons[action]}</svg><span>${label}</span>`;
      menu.appendChild(item);
    }
    menu.hidden = false;
    const rect = button.getBoundingClientRect();
    const left = Math.max(8, Math.min(rect.right - menu.offsetWidth, window.innerWidth - menu.offsetWidth - 8));
    const top = rect.bottom + menu.offsetHeight + 6 <= window.innerHeight - 8
      ? rect.bottom + 6 : Math.max(8, rect.top - menu.offsetHeight - 6);
    menu.style.left = `${left}px`;
    menu.style.top = `${top}px`;
    (focusLast ? menu.lastElementChild : menu.firstElementChild).focus({ preventScroll: true });
  }

  document.addEventListener("click", (evento) => {
    const button = evento.target.closest("[data-row-actions]");
    if (button) {
      if (trigger === button) closeMenu(true);
      else openMenu(button);
    } else if (trigger && !menu.contains(evento.target)) closeMenu();
  });
  document.addEventListener("pointerdown", (evento) => {
    if (trigger && !menu.contains(evento.target) && !evento.target.closest("[data-row-actions]")) closeMenu();
  });
  menu.addEventListener("click", (evento) => {
    const item = evento.target.closest("[data-action]");
    if (!item || !trigger) return;
    const detail = { trigger, action: item.dataset.action };
    closeMenu(true);
    detail.trigger.dispatchEvent(new CustomEvent("rowaction", { bubbles: true, detail }));
  });
  document.addEventListener("keydown", (evento) => {
    const button = evento.target.closest("[data-row-actions]");
    if (button && (evento.key === "ArrowDown" || evento.key === "ArrowUp")) {
      evento.preventDefault();
      openMenu(button, evento.key === "ArrowUp");
      return;
    }
    if (!trigger) return;
    if (evento.key === "Escape") {
      evento.preventDefault();
      closeMenu(true);
      return;
    }
    if (!menu.contains(evento.target)) return;
    if (evento.key === "Tab") {
      closeMenu(true);
      return;
    }
    const items = [...menu.querySelectorAll('[role="menuitem"]')];
    const index = items.indexOf(document.activeElement);
    let next;
    if (evento.key === "ArrowDown") next = (index + 1) % items.length;
    else if (evento.key === "ArrowUp") next = (index - 1 + items.length) % items.length;
    else if (evento.key === "Home") next = 0;
    else if (evento.key === "End") next = items.length - 1;
    else return;
    evento.preventDefault();
    items[next].focus();
  });
  document.addEventListener("scroll", () => closeMenu(), true);
  window.addEventListener("resize", () => closeMenu());
  new MutationObserver(() => {
    if (trigger && !trigger.isConnected) closeMenu();
  }).observe(document.body, { childList: true, subtree: true });
});

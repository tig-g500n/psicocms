(() => {
    "use strict";

    const toggle = document.querySelector("[data-nav-toggle]");
    const menu = document.querySelector("[data-nav-menu]");
    if (!toggle || !menu) return;

    const cerrar = () => menu.classList.remove("is-open");

    toggle.addEventListener("click", (evento) => {
        evento.preventDefault();
        menu.classList.toggle("is-open");
    });

    menu.querySelectorAll("a").forEach((enlace) => {
        enlace.addEventListener("click", cerrar);
    });

    document.addEventListener("keydown", (evento) => {
        if (evento.key === "Escape") cerrar();
    });
})();

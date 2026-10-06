(() => {
    "use strict";

    const cerrarTodos = (excepto) => {
        document.querySelectorAll("[data-icono-panel]").forEach((panel) => {
            if (panel !== excepto) panel.hidden = true;
        });
    };

    document.addEventListener("click", (evento) => {
        const toggle = evento.target.closest("[data-icono-toggle]");
        const opcion = evento.target.closest("[data-icono]");

        if (toggle) {
            evento.preventDefault();
            const panel = toggle.closest("[data-icono-picker]").querySelector("[data-icono-panel]");
            const estabaAbierto = !panel.hidden;
            cerrarTodos(panel);
            panel.hidden = estabaAbierto;
            return;
        }

        if (opcion) {
            evento.preventDefault();
            const picker = opcion.closest("[data-icono-picker]");
            const valor = opcion.getAttribute("data-icono");

            picker.querySelector("[data-icono-valor]").value = valor;
            picker.querySelector("[data-icono-preview]").className = "fa-solid " + valor;
            picker.querySelectorAll(".icono-opcion").forEach((o) => o.classList.toggle("is-sel", o === opcion));
            picker.querySelector("[data-icono-panel]").hidden = true;
            return;
        }

        if (!evento.target.closest("[data-icono-panel]")) {
            cerrarTodos(null);
        }
    });

    document.addEventListener("keydown", (evento) => {
        if (evento.key === "Escape") cerrarTodos(null);
    });
})();

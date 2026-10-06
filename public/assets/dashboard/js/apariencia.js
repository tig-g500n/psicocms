(() => {
    "use strict";

    const modal = document.getElementById("modal-apariencia");
    if (!modal) return;

    const root = document.documentElement;
    const form = modal.querySelector("form");

    const leerSeleccion = () => {
        const tema = modal.querySelector("[name=apariencia]:checked")?.value || "claro";
        const color = modal.querySelector("[name=color]:checked");
        return {
            tema,
            color: color?.value || "",
            primary: color?.dataset.primary || "",
            dark: color?.dataset.dark || "",
        };
    };

    const inicial = leerSeleccion();

    const aplicar = (estado) => {
        root.setAttribute("data-tema", estado.tema);
        if (estado.primary) root.style.setProperty("--dash-primary", estado.primary);
        if (estado.dark) root.style.setProperty("--dash-primary-dark", estado.dark);
    };

    const revertir = () => {
        const tema = modal.querySelector(`[name=apariencia][value="${inicial.tema}"]`);
        const color = modal.querySelector(`[name=color][value="${inicial.color}"]`);
        if (tema) tema.checked = true;
        if (color) color.checked = true;
        aplicar(inicial);
    };

    document.querySelectorAll("[data-apariencia-open]").forEach((boton) => {
        boton.addEventListener("click", (evento) => {
            evento.preventDefault();
            document.querySelectorAll("[data-dropdown-menu]").forEach((m) => m.classList.remove("is-open"));
            window.PsicoModal?.abrir("modal-apariencia");
        });
    });

    modal.querySelectorAll("[name=apariencia], [name=color]").forEach((input) => {
        input.addEventListener("change", () => aplicar(leerSeleccion()));
    });

    let enviando = false;
    form?.addEventListener("submit", () => { enviando = true; });

    modal.querySelectorAll("[data-modal-close]").forEach((el) => {
        el.addEventListener("click", () => { if (!enviando) revertir(); });
    });

    document.addEventListener("keydown", (evento) => {
        if (evento.key === "Escape" && modal.classList.contains("is-open") && !enviando) {
            revertir();
        }
    });
})();

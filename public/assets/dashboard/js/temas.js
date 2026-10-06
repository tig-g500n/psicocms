(() => {
    const form = document.querySelector("[data-temas-form]");
    if (!form) return;

    const modoInputs = form.querySelectorAll("[data-modo]");
    const previewLinks = document.querySelectorAll("[data-preview-link]");

    const modoActual = () => {
        const marcado = form.querySelector("[data-modo]:checked");
        return marcado ? marcado.value : "";
    };

    const sincronizar = () => {
        const modo = modoActual();
        previewLinks.forEach((link) => {
            const base = link.dataset.previewBase;
            link.href = modo ? `${base}?modo=${encodeURIComponent(modo)}` : base;
        });
    };

    modoInputs.forEach((input) => input.addEventListener("change", sincronizar));
    sincronizar();
})();

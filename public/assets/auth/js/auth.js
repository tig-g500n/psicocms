(() => {
    "use strict";

    document.addEventListener("DOMContentLoaded", () => {
        const boton = document.querySelector("[data-toggle-password]");
        const campo = document.getElementById("password");
        if (!boton || !campo) {
            return;
        }

        boton.addEventListener("click", (evento) => {
            evento.preventDefault();
            const oculto = campo.getAttribute("type") === "password";
            campo.setAttribute("type", oculto ? "text" : "password");
            const icono = boton.querySelector("i");
            if (icono) {
                icono.classList.toggle("fa-eye", !oculto);
                icono.classList.toggle("fa-eye-slash", oculto);
            }
        });
    });
})();

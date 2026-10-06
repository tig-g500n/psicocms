(() => {
    "use strict";

    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll("[data-repeatable]").forEach((grupo) => {
            const plantilla = grupo.querySelector("[data-template]");
            const boton = grupo.parentElement.querySelector("[data-add]");
            if (!plantilla || !boton) {
                return;
            }

            let contador = grupo.querySelectorAll(".rep-row").length;

            boton.addEventListener("click", (evento) => {
                evento.preventDefault();
                const fragmento = plantilla.content.cloneNode(true);
                fragmento.querySelectorAll("[name]").forEach((campo) => {
                    campo.setAttribute("name", campo.getAttribute("name").replace("__INDEX__", contador));
                });
                grupo.insertBefore(fragmento, plantilla);
                contador += 1;
            });

            grupo.addEventListener("click", (evento) => {
                const quitar = evento.target.closest("[data-remove]");
                if (!quitar) {
                    return;
                }
                evento.preventDefault();
                const fila = quitar.closest(".rep-row");
                if (fila) {
                    fila.remove();
                }
            });
        });
    });
})();

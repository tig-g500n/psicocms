(() => {
    "use strict";

    const initRepeatables = () => {
        const grupos = document.querySelectorAll("[data-repeatable]");

        grupos.forEach((grupo) => {
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
    };

    const initFotoPreview = () => {
        const input = document.querySelector("[data-foto-input]");
        const preview = document.querySelector("[data-preview]");
        if (!input || !preview) {
            return;
        }

        input.addEventListener("change", () => {
            const archivo = input.files && input.files[0];
            if (!archivo) {
                return;
            }
            const lector = new FileReader();
            lector.addEventListener("load", (evento) => {
                while (preview.firstChild) {
                    preview.removeChild(preview.firstChild);
                }
                const imagen = document.createElement("img");
                imagen.src = evento.target.result;
                imagen.alt = "Vista previa";
                preview.appendChild(imagen);
            });
            lector.readAsDataURL(archivo);
        });
    };

    document.addEventListener("DOMContentLoaded", () => {
        initRepeatables();
        initFotoPreview();
    });
})();

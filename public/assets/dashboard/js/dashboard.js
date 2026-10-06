(() => {
    "use strict";

    /* ===== Sidebar móvil ===== */
    const initSidebar = () => {
        const sidebar = document.querySelector("[data-sidebar]");
        const overlay = document.querySelector("[data-sidebar-overlay]");
        const toggles = document.querySelectorAll("[data-sidebar-toggle]");
        if (!sidebar) {
            return;
        }

        const abrir = () => {
            sidebar.classList.add("is-open");
            overlay?.classList.add("is-visible");
        };
        const cerrar = () => {
            sidebar.classList.remove("is-open");
            overlay?.classList.remove("is-visible");
        };

        toggles.forEach((boton) => {
            boton.addEventListener("click", (evento) => {
                evento.preventDefault();
                sidebar.classList.contains("is-open") ? cerrar() : abrir();
            });
        });
        overlay?.addEventListener("click", cerrar);
    };

    /* ===== Grupos desplegables del menú ===== */
    const initGrupos = () => {
        document.querySelectorAll("[data-group-toggle]").forEach((boton) => {
            boton.addEventListener("click", (evento) => {
                evento.preventDefault();
                const grupo = boton.closest("[data-group]");
                grupo?.classList.toggle("is-open");
            });
        });
    };

    /* ===== Dropdowns del header ===== */
    const initDropdowns = () => {
        const wraps = document.querySelectorAll("[data-dropdown]");

        const cerrarTodos = () => {
            wraps.forEach((wrap) => {
                wrap.querySelector("[data-dropdown-menu]")?.classList.remove("is-open");
            });
        };

        wraps.forEach((wrap) => {
            const boton = wrap.querySelector("[data-dropdown-toggle]");
            const menu = wrap.querySelector("[data-dropdown-menu]");
            if (!boton || !menu) {
                return;
            }
            boton.addEventListener("click", (evento) => {
                evento.preventDefault();
                evento.stopPropagation();
                const abierto = menu.classList.contains("is-open");
                cerrarTodos();
                if (!abierto) {
                    menu.classList.add("is-open");
                }
            });
            menu.addEventListener("click", (evento) => evento.stopPropagation());
        });

        document.addEventListener("click", cerrarTodos);
        document.addEventListener("keydown", (evento) => {
            if (evento.key === "Escape") {
                cerrarTodos();
            }
        });
    };

    /* ===== Modales ===== */
    const initModales = () => {
        const abrir = (id) => document.getElementById(id)?.classList.add("is-open");
        const cerrar = (modal) => modal?.classList.remove("is-open");

        document.querySelectorAll("[data-modal-open]").forEach((boton) => {
            boton.addEventListener("click", (evento) => {
                evento.preventDefault();
                abrir(boton.getAttribute("data-modal-open"));
            });
        });

        document.querySelectorAll("[data-modal]").forEach((modal) => {
            modal.querySelectorAll("[data-modal-close]").forEach((elemento) => {
                elemento.addEventListener("click", (evento) => {
                    evento.preventDefault();
                    cerrar(modal);
                });
            });
        });

        document.addEventListener("keydown", (evento) => {
            if (evento.key === "Escape") {
                document.querySelectorAll("[data-modal].is-open").forEach(cerrar);
            }
        });

        window.PsicoModal = { abrir, cerrar: (id) => cerrar(document.getElementById(id)) };
    };

    /* ===== Toasts ===== */
    const initToasts = () => {
        const stack = document.querySelector("[data-toast-stack]");
        const iconos = {
            exito: "fa-circle-check",
            error: "fa-circle-exclamation",
            aviso: "fa-triangle-exclamation",
            info: "fa-circle-info",
        };
        const clases = {
            exito: "toast--success",
            error: "toast--error",
            aviso: "toast--warning",
            info: "",
        };

        const mostrar = (mensaje, tipo) => {
            if (!stack) {
                return;
            }
            const toast = document.createElement("div");
            toast.className = "toast " + (clases[tipo] || "");

            const icono = document.createElement("i");
            icono.className = "toast__icon fa-solid " + (iconos[tipo] || iconos.info);

            const cuerpo = document.createElement("div");
            cuerpo.className = "toast__body";
            cuerpo.textContent = mensaje;

            const cerrar = document.createElement("button");
            cerrar.type = "button";
            cerrar.className = "toast__close";
            cerrar.setAttribute("aria-label", "Cerrar");
            const equis = document.createElement("i");
            equis.className = "fa-solid fa-xmark";
            cerrar.appendChild(equis);

            toast.appendChild(icono);
            toast.appendChild(cuerpo);
            toast.appendChild(cerrar);
            stack.appendChild(toast);

            const quitar = () => {
                toast.classList.add("is-leaving");
                toast.addEventListener("animationend", () => toast.remove(), { once: true });
            };

            cerrar.addEventListener("click", quitar);
            window.setTimeout(quitar, 4500);
        };

        window.PsicoToast = {
            exito: (m) => mostrar(m, "exito"),
            error: (m) => mostrar(m, "error"),
            aviso: (m) => mostrar(m, "aviso"),
            info: (m) => mostrar(m, "info"),
        };
    };

    /* ===== Confirmación en formularios ===== */
    const initConfirm = () => {
        const modal = document.getElementById("modal-confirmar");
        if (!modal) {
            return;
        }
        const texto = modal.querySelector("[data-confirm-text]");
        const aceptar = modal.querySelector("[data-confirm-accept]");
        let formularioPendiente = null;

        document.querySelectorAll("form[data-confirm]").forEach((form) => {
            form.addEventListener("submit", (evento) => {
                if (form.dataset.confirmado === "1") {
                    return;
                }
                evento.preventDefault();
                formularioPendiente = form;
                if (texto) {
                    texto.textContent = form.getAttribute("data-confirm");
                }
                modal.classList.add("is-open");
            });
        });

        aceptar?.addEventListener("click", () => {
            if (formularioPendiente) {
                formularioPendiente.dataset.confirmado = "1";
                formularioPendiente.requestSubmit();
            }
            modal.classList.remove("is-open");
        });
    };

    /* ===== Preview de imagen ===== */
    const initImagenPreview = () => {
        document.querySelectorAll("[data-img-input]").forEach((input) => {
            const preview = document.querySelector(input.getAttribute("data-img-input"));
            if (!preview) {
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
        });
    };

    /* ===== Lista de ficheros seleccionados ===== */
    const initFileList = () => {
        document.querySelectorAll("[data-file-list]").forEach((input) => {
            const destino = document.querySelector(input.getAttribute("data-file-list"));
            if (!destino) {
                return;
            }
            input.addEventListener("change", () => {
                while (destino.firstChild) {
                    destino.removeChild(destino.firstChild);
                }
                Array.from(input.files).forEach((archivo) => {
                    const item = document.createElement("span");
                    item.className = "file-chip";
                    const icono = document.createElement("i");
                    icono.className = archivo.type === "application/pdf" ? "fa-solid fa-file-pdf" : "fa-solid fa-image";
                    const texto = document.createElement("span");
                    texto.textContent = archivo.name;
                    item.appendChild(icono);
                    item.appendChild(texto);
                    destino.appendChild(item);
                });
            });
        });
    };

    /* ===== Pestañas genéricas ===== */
    const initTabs = () => {
        document.querySelectorAll("[data-tabs]").forEach((contenedor) => {
            const tabs = contenedor.querySelectorAll("[data-tab]");
            tabs.forEach((tab) => {
                tab.addEventListener("click", (evento) => {
                    evento.preventDefault();
                    const destino = tab.getAttribute("data-tab");
                    tabs.forEach((t) => t.classList.toggle("is-active", t === tab));
                    contenedor.querySelectorAll("[data-panel]").forEach((panel) => {
                        panel.hidden = panel.getAttribute("data-panel") !== destino;
                    });
                });
            });
        });
    };

    document.addEventListener("DOMContentLoaded", () => {
        initSidebar();
        initGrupos();
        initDropdowns();
        initModales();
        initToasts();
        initConfirm();
        initImagenPreview();
        initFileList();
        initTabs();
    });
})();

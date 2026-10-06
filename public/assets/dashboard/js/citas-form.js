(() => {
    "use strict";

    const form = document.querySelector("[data-cita-form]");
    if (!form) {
        return;
    }

    const url = form.getAttribute("data-huecos-url");
    const except = form.getAttribute("data-except");
    const horaActual = form.getAttribute("data-hora-actual");

    const selModalidad = form.querySelector("[data-modalidad]");
    const inputFecha = form.querySelector("[data-fecha]");
    const selHora = form.querySelector("[data-hora]");
    const aviso = form.querySelector("[data-hora-aviso]");

    const limpiar = (elemento) => {
        while (elemento.firstChild) {
            elemento.removeChild(elemento.firstChild);
        }
    };

    const opcion = (valor, texto, seleccionada) => {
        const op = document.createElement("option");
        op.value = valor;
        op.textContent = texto;
        if (seleccionada) {
            op.selected = true;
        }
        return op;
    };

    const cargar = async (preservar) => {
        aviso.textContent = "";
        if (!inputFecha.value || !selModalidad.value) {
            return;
        }

        const parametros = new URLSearchParams({
            modalidad: selModalidad.value,
            fecha: inputFecha.value,
        });
        if (except) {
            parametros.append("except", except);
        }

        try {
            const respuesta = await fetch(url + "?" + parametros.toString(), {
                headers: { Accept: "application/json" },
            });
            const datos = await respuesta.json();

            limpiar(selHora);

            if (datos.bloqueada) {
                const esModo = datos.motivo === "modo";
                selHora.appendChild(opcion("", esModo ? "Modo vacaciones activo" : "Día en periodo de vacaciones", false));
                aviso.textContent = esModo
                    ? "El «Modo vacaciones» global está activado: el sistema de reservas está pausado. Desactívalo en Disponibilidad."
                    : "Este día está dentro de un periodo de vacaciones configurado.";
                return;
            }

            const huecos = datos.huecos || [];
            const actual = preservar ? horaActual : "";

            if (actual && !huecos.includes(actual)) {
                huecos.unshift(actual);
            }

            if (huecos.length === 0) {
                selHora.appendChild(opcion("", "Sin huecos disponibles", false));
                aviso.textContent = "No hay huecos disponibles ese día. Revisa tu disponibilidad.";
                return;
            }

            huecos.forEach((hora) => {
                selHora.appendChild(opcion(hora, hora, hora === actual));
            });
        } catch (error) {
            aviso.textContent = "No se pudieron cargar los horarios.";
        }
    };

    selModalidad.addEventListener("change", () => cargar(false));
    inputFecha.addEventListener("change", () => cargar(false));

    /* ===== Autocompletado de pacientes ===== */
    const initAutocomplete = () => {
        const buscarUrl = form.getAttribute("data-buscar-url");
        const inputNombre = form.querySelector("[data-paciente-nombre]");
        const inputTelefono = form.querySelector("[data-paciente-telefono]");
        const lista = form.querySelector("[data-sugerencias]");
        if (!buscarUrl || !inputNombre || !lista) {
            return;
        }

        let temporizador = null;

        const ocultar = () => {
            lista.hidden = true;
            while (lista.firstChild) {
                lista.removeChild(lista.firstChild);
            }
        };

        const elegir = (paciente) => {
            inputNombre.value = paciente.nombre;
            inputTelefono.value = paciente.telefono;
            if (paciente.modalidad_pref && selModalidad) {
                selModalidad.value = paciente.modalidad_pref;
                selModalidad.dispatchEvent(new Event("change"));
            }
            ocultar();
        };

        const pintar = (pacientes) => {
            while (lista.firstChild) {
                lista.removeChild(lista.firstChild);
            }
            if (pacientes.length === 0) {
                ocultar();
                return;
            }
            pacientes.forEach((paciente) => {
                const item = document.createElement("button");
                item.type = "button";
                item.className = "autocomplete__item";
                const nombre = document.createElement("strong");
                nombre.textContent = paciente.nombre;
                const tel = document.createElement("span");
                tel.textContent = paciente.telefono;
                item.appendChild(nombre);
                item.appendChild(tel);
                item.addEventListener("click", () => elegir(paciente));
                lista.appendChild(item);
            });
            lista.hidden = false;
        };

        inputNombre.addEventListener("input", () => {
            const q = inputNombre.value.trim();
            window.clearTimeout(temporizador);
            if (q.length < 2) {
                ocultar();
                return;
            }
            temporizador = window.setTimeout(async () => {
                try {
                    const resp = await fetch(buscarUrl + "?q=" + encodeURIComponent(q), { headers: { Accept: "application/json" } });
                    const datos = await resp.json();
                    pintar(datos.pacientes || []);
                } catch (error) {
                    ocultar();
                }
            }, 250);
        });

        document.addEventListener("click", (evento) => {
            if (!evento.target.closest(".autocomplete")) {
                ocultar();
            }
        });
    };

    initAutocomplete();

    document.addEventListener("DOMContentLoaded", () => {
        if (inputFecha.value) {
            cargar(true);
        }
    });
})();

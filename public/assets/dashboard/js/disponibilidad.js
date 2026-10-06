(() => {
    "use strict";

    const minutos = (hhmm) => {
        const [h, m] = hhmm.split(":");
        return parseInt(h, 10) * 60 + parseInt(m, 10);
    };

    const hhmm = (min) => {
        const h = Math.floor(min / 60);
        const m = min % 60;
        return String(h).padStart(2, "0") + ":" + String(m).padStart(2, "0");
    };

    const candidatas = (panel) => {
        const duracion = parseInt(panel.querySelector("[data-duracion]").value, 10) || 0;
        const entrada = panel.querySelector("[data-entrada]").value || "09:00";
        const salida = panel.querySelector("[data-salida]").value || "18:00";
        const activo = panel.querySelector("[data-descanso-activo]").checked;
        const descanso = parseInt(panel.querySelector("[data-descanso]").value, 10) || 0;
        const paso = Math.max(1, duracion + (activo ? descanso : 0));

        const horas = [];
        const fin = minutos(salida);
        for (let t = minutos(entrada); t + duracion <= fin; t += paso) {
            horas.push(hhmm(t));
        }
        return horas;
    };

    const marcados = (panel) => {
        const set = new Set();
        panel.querySelectorAll('.disp-slot input:checked').forEach((input) => set.add(input.value));
        return set;
    };

    const reconstruir = (panel) => {
        const horas = candidatas(panel);
        const seleccion = marcados(panel);
        const columnas = panel.querySelectorAll(".disp-col");

        columnas.forEach((columna, dia) => {
            const contenedor = columna.querySelector(".disp-col__slots");
            while (contenedor.firstChild) {
                contenedor.removeChild(contenedor.firstChild);
            }

            if (horas.length === 0) {
                const vacio = document.createElement("span");
                vacio.className = "disp-col__vacio";
                vacio.textContent = "—";
                contenedor.appendChild(vacio);
                return;
            }

            horas.forEach((hora) => {
                const valor = dia + "|" + hora;
                const label = document.createElement("label");
                label.className = "disp-slot";

                const input = document.createElement("input");
                input.type = "checkbox";
                input.name = "slots[]";
                input.value = valor;
                if (seleccion.has(valor)) {
                    input.checked = true;
                }

                const span = document.createElement("span");
                span.textContent = hora;

                label.appendChild(input);
                label.appendChild(span);
                contenedor.appendChild(label);
            });
        });
    };

    const initPanel = (panel) => {
        const aviso = panel.querySelector("[data-aviso]");
        const disparadores = panel.querySelectorAll("[data-duracion],[data-entrada],[data-salida],[data-descanso],[data-descanso-activo]");

        disparadores.forEach((campo) => {
            campo.addEventListener("change", () => {
                reconstruir(panel);
                if (aviso) {
                    aviso.hidden = false;
                }
            });
        });
    };

    const initTabs = () => {
        const tabs = document.querySelectorAll(".disp-tab");
        tabs.forEach((tab) => {
            tab.addEventListener("click", (evento) => {
                evento.preventDefault();
                const modalidad = tab.getAttribute("data-tab");
                tabs.forEach((t) => t.classList.toggle("is-active", t === tab));
                document.querySelectorAll(".disp-panel").forEach((panel) => {
                    panel.hidden = panel.getAttribute("data-modalidad") !== modalidad;
                });
            });
        });
    };

    document.addEventListener("DOMContentLoaded", () => {
        initTabs();
        document.querySelectorAll(".disp-panel").forEach(initPanel);
    });
})();

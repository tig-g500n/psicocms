(() => {
    "use strict";

    const raiz = document.querySelector("[data-calendario]");
    if (!raiz) {
        return;
    }

    const eventosUrl = raiz.getAttribute("data-eventos-url");
    const crearUrl = raiz.getAttribute("data-crear-url");

    const cuerpo = raiz.querySelector("[data-cal-cuerpo]");
    const tituloEl = raiz.querySelector("[data-cal-titulo]");
    const agendaTitulo = raiz.querySelector("[data-cal-agenda-titulo]");
    const agendaFecha = raiz.querySelector("[data-cal-agenda-fecha]");
    const agendaLista = raiz.querySelector("[data-cal-agenda-lista]");
    const botonNueva = raiz.querySelector("[data-cal-nueva]");

    const MESES = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    const DIAS_LARGO = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    const DIAS_CORTO = ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"];

    let vista = "mes";
    let cursor = new Date();
    let seleccionado = new Date();
    let eventosPorDia = new Map();

    const pad = (n) => String(n).padStart(2, "0");
    const iso = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    const mismoDia = (a, b) => iso(a) === iso(b);
    const hoyEs = (d) => mismoDia(d, new Date());
    const sumarDias = (d, n) => new Date(d.getFullYear(), d.getMonth(), d.getDate() + n);
    const lunesDe = (d) => sumarDias(d, -((d.getDay() + 6) % 7));

    const limpiar = (el) => {
        while (el.firstChild) {
            el.removeChild(el.firstChild);
        }
    };

    const rangoVisible = () => {
        if (vista === "mes") {
            const primero = new Date(cursor.getFullYear(), cursor.getMonth(), 1);
            const inicio = lunesDe(primero);
            return [inicio, sumarDias(inicio, 41)];
        }
        if (vista === "semana") {
            const inicio = lunesDe(cursor);
            return [inicio, sumarDias(inicio, 6)];
        }
        return [cursor, cursor];
    };

    const cargarYRender = async () => {
        const [desde, hasta] = rangoVisible();
        const params = new URLSearchParams({ desde: iso(desde), hasta: iso(hasta) });
        try {
            const resp = await fetch(eventosUrl + "?" + params.toString(), { headers: { Accept: "application/json" } });
            const datos = await resp.json();
            eventosPorDia = new Map();
            (datos.eventos || []).forEach((ev) => {
                if (!eventosPorDia.has(ev.fecha)) {
                    eventosPorDia.set(ev.fecha, []);
                }
                eventosPorDia.get(ev.fecha).push(ev);
            });
        } catch (error) {
            eventosPorDia = new Map();
        }
        renderVista();
        renderAgenda();
    };

    const eventosDe = (fecha) => (eventosPorDia.get(fecha) || []).slice().sort((a, b) => a.hora.localeCompare(b.hora));

    const claseModalidad = (ev, base) => {
        if (ev.estado === "cancelada") {
            return base + "--cancelada";
        }
        return ev.modalidad === "presencial" ? base + "--presencial" : "";
    };

    const irAEvento = (ev) => { window.location.href = ev.url; };

    /* ===== Vista Mes ===== */
    const renderMes = () => {
        const cabecera = document.createElement("div");
        cabecera.className = "cal-mes__dias";
        DIAS_CORTO.forEach((d) => {
            const c = document.createElement("div");
            c.className = "cal-mes__dia-label";
            c.textContent = d;
            cabecera.appendChild(c);
        });

        const grid = document.createElement("div");
        grid.className = "cal-mes__grid";

        const [inicio] = rangoVisible();
        for (let i = 0; i < 42; i += 1) {
            const dia = sumarDias(inicio, i);
            const celda = document.createElement("div");
            celda.className = "cal-celda";
            if (dia.getMonth() !== cursor.getMonth()) {
                celda.classList.add("cal-celda--otro");
            }
            if (hoyEs(dia)) {
                celda.classList.add("cal-celda--hoy");
            }
            if (mismoDia(dia, seleccionado)) {
                celda.classList.add("cal-celda--sel");
            }

            const num = document.createElement("span");
            num.className = "cal-celda__num";
            num.textContent = dia.getDate();
            celda.appendChild(num);

            const lista = eventosDe(iso(dia));
            lista.slice(0, 3).forEach((ev) => {
                const chip = document.createElement("button");
                chip.type = "button";
                chip.className = "cal-evento " + claseModalidad(ev, "cal-evento");
                chip.textContent = ev.hora + " " + ev.paciente;
                chip.addEventListener("click", (e) => { e.stopPropagation(); irAEvento(ev); });
                celda.appendChild(chip);
            });
            if (lista.length > 3) {
                const mas = document.createElement("span");
                mas.className = "cal-evento__mas";
                mas.textContent = "+" + (lista.length - 3) + " más";
                celda.appendChild(mas);
            }

            celda.addEventListener("click", () => {
                seleccionado = dia;
                renderVista();
                renderAgenda();
            });

            grid.appendChild(celda);
        }

        limpiar(cuerpo);
        cuerpo.appendChild(cabecera);
        cuerpo.appendChild(grid);
    };

    /* ===== Vista lista (semana / día) ===== */
    const renderLista = (dias) => {
        const cont = document.createElement("div");
        cont.className = "cal-lista";

        dias.forEach((dia) => {
            const bloque = document.createElement("div");
            bloque.className = "cal-lista__dia";

            const cab = document.createElement("div");
            cab.className = "cal-lista__cab" + (hoyEs(dia) ? " cal-lista__cab--hoy" : "");
            const titulo = document.createElement("span");
            titulo.textContent = DIAS_LARGO[dia.getDay()] + ", " + dia.getDate() + " de " + MESES[dia.getMonth()];
            cab.appendChild(titulo);
            const anadir = document.createElement("a");
            anadir.href = crearUrl + "?fecha=" + iso(dia);
            anadir.className = "btn btn--soft btn--sm";
            const iAdd = document.createElement("i");
            iAdd.className = "fa-solid fa-plus";
            anadir.appendChild(iAdd);
            cab.appendChild(anadir);
            bloque.appendChild(cab);

            const citas = document.createElement("div");
            citas.className = "cal-lista__citas";
            const lista = eventosDe(iso(dia));
            if (lista.length === 0) {
                const vacio = document.createElement("div");
                vacio.className = "cal-lista__vacio";
                vacio.textContent = "Sin citas.";
                citas.appendChild(vacio);
            } else {
                lista.forEach((ev) => {
                    const fila = document.createElement("button");
                    fila.type = "button";
                    fila.className = "cal-lista__cita";
                    const hora = document.createElement("span");
                    hora.className = "cal-lista__hora";
                    hora.textContent = ev.hora;
                    const info = document.createElement("div");
                    const nombre = document.createElement("div");
                    nombre.className = "agenda-item__nombre";
                    nombre.textContent = ev.paciente;
                    const meta = document.createElement("div");
                    meta.className = "agenda-item__meta";
                    meta.textContent = (ev.modalidad === "online" ? "Online" : "Presencial") + " · " + ev.hora + "-" + ev.hora_fin + " · " + ev.estado;
                    info.appendChild(nombre);
                    info.appendChild(meta);
                    fila.appendChild(hora);
                    fila.appendChild(info);
                    fila.addEventListener("click", () => irAEvento(ev));
                    citas.appendChild(fila);
                });
            }
            bloque.appendChild(citas);
            cont.appendChild(bloque);
        });

        limpiar(cuerpo);
        cuerpo.appendChild(cont);
    };

    const renderVista = () => {
        actualizarTitulo();
        if (vista === "mes") {
            renderMes();
        } else if (vista === "semana") {
            const inicio = lunesDe(cursor);
            renderLista(Array.from({ length: 7 }, (_, i) => sumarDias(inicio, i)));
        } else {
            seleccionado = cursor;
            renderLista([cursor]);
        }
    };

    const actualizarTitulo = () => {
        if (vista === "mes") {
            tituloEl.textContent = MESES[cursor.getMonth()] + " " + cursor.getFullYear();
        } else if (vista === "semana") {
            const inicio = lunesDe(cursor);
            const fin = sumarDias(inicio, 6);
            tituloEl.textContent = inicio.getDate() + " " + MESES[inicio.getMonth()] + " - " + fin.getDate() + " " + MESES[fin.getMonth()];
        } else {
            tituloEl.textContent = DIAS_LARGO[cursor.getDay()] + ", " + cursor.getDate() + " " + MESES[cursor.getMonth()];
        }
    };

    /* ===== Agenda del día ===== */
    const renderAgenda = () => {
        agendaFecha.textContent = DIAS_LARGO[seleccionado.getDay()] + ", " + seleccionado.getDate() + " de " + MESES[seleccionado.getMonth()] + " de " + seleccionado.getFullYear();
        botonNueva.href = crearUrl + "?fecha=" + iso(seleccionado);

        limpiar(agendaLista);
        const lista = eventosDe(iso(seleccionado));

        if (lista.length === 0) {
            const vacio = document.createElement("div");
            vacio.className = "cal__agenda-vacio";
            const icono = document.createElement("i");
            icono.className = "fa-solid fa-mug-hot";
            const texto = document.createElement("p");
            texto.textContent = "No hay citas programadas para este día.";
            vacio.appendChild(icono);
            vacio.appendChild(texto);
            agendaLista.appendChild(vacio);
            return;
        }

        lista.forEach((ev) => {
            const item = document.createElement("button");
            item.type = "button";
            item.className = "agenda-item " + claseModalidad(ev, "agenda-item");

            const hora = document.createElement("div");
            hora.className = "agenda-item__hora";
            const h = document.createElement("strong");
            h.textContent = ev.hora;
            const fin = document.createElement("span");
            fin.textContent = ev.hora_fin;
            hora.appendChild(h);
            hora.appendChild(fin);

            const cuerpoItem = document.createElement("div");
            cuerpoItem.className = "agenda-item__cuerpo";
            const nombre = document.createElement("div");
            nombre.className = "agenda-item__nombre";
            nombre.textContent = ev.paciente;
            const meta = document.createElement("div");
            meta.className = "agenda-item__meta";
            const iMeta = document.createElement("i");
            iMeta.className = ev.modalidad === "online" ? "fa-solid fa-video" : "fa-solid fa-location-dot";
            const spanMeta = document.createElement("span");
            spanMeta.textContent = (ev.modalidad === "online" ? "Online" : "Presencial") + " · " + ev.estado;
            meta.appendChild(iMeta);
            meta.appendChild(spanMeta);
            cuerpoItem.appendChild(nombre);
            cuerpoItem.appendChild(meta);

            item.appendChild(hora);
            item.appendChild(cuerpoItem);
            item.addEventListener("click", () => irAEvento(ev));
            agendaLista.appendChild(item);
        });
    };

    /* ===== Navegación ===== */
    const paso = (dir) => {
        if (vista === "mes") {
            cursor = new Date(cursor.getFullYear(), cursor.getMonth() + dir, 1);
        } else if (vista === "semana") {
            cursor = sumarDias(cursor, dir * 7);
        } else {
            cursor = sumarDias(cursor, dir);
        }
        cargarYRender();
    };

    raiz.querySelector("[data-cal-prev]").addEventListener("click", () => paso(-1));
    raiz.querySelector("[data-cal-next]").addEventListener("click", () => paso(1));
    raiz.querySelector("[data-cal-hoy]").addEventListener("click", () => {
        cursor = new Date();
        seleccionado = new Date();
        cargarYRender();
    });

    raiz.querySelectorAll("[data-cal-vista]").forEach((boton) => {
        boton.addEventListener("click", () => {
            vista = boton.getAttribute("data-cal-vista");
            raiz.querySelectorAll("[data-cal-vista]").forEach((b) => b.classList.toggle("is-active", b === boton));
            cargarYRender();
        });
    });

    cargarYRender();
})();

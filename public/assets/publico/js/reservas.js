(() => {
    "use strict";

    const raiz = document.querySelector("[data-reserva]");
    if (!raiz) return;

    const urls = {
        dias: raiz.dataset.urlDias,
        horas: raiz.dataset.urlHoras,
        store: raiz.dataset.urlStore,
    };
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || "";

    const pasoFecha = raiz.querySelector("[data-paso-fecha]");
    const pasoHora = raiz.querySelector("[data-paso-hora]");
    const form = raiz.querySelector("[data-reserva-form]");
    const contenedorDias = raiz.querySelector("[data-cal-dias]");
    const titulo = raiz.querySelector("[data-cal-titulo]");
    const contenedorHoras = raiz.querySelector("[data-horas]");
    const errorBox = raiz.querySelector("[data-reserva-error]");
    const submitBtn = raiz.querySelector("[data-reserva-submit]");

    const modal = document.querySelector("[data-reserva-modal]");
    const resumen = modal?.querySelector("[data-reserva-resumen]");
    const googleBtn = modal?.querySelector("[data-reserva-google]");

    const MESES = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    const estado = { modalidad: null, fecha: null, hora: null };
    const hoy = new Date();
    let vista = new Date(hoy.getFullYear(), hoy.getMonth(), 1);

    const pad = (n) => String(n).padStart(2, "0");
    const iso = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    const vaciar = (el) => { while (el.firstChild) el.removeChild(el.firstChild); };
    const hoyIso = iso(hoy);

    raiz.querySelectorAll("[data-modalidad]").forEach((btn) => {
        btn.addEventListener("click", () => {
            estado.modalidad = btn.dataset.modalidad;
            estado.fecha = null;
            estado.hora = null;
            raiz.querySelectorAll("[data-modalidad]").forEach((b) => b.classList.toggle("is-active", b === btn));
            pasoFecha.hidden = false;
            pasoHora.hidden = true;
            form.hidden = true;
            vista = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            renderCalendario();
        });
    });

    raiz.querySelector("[data-cal-prev]").addEventListener("click", () => {
        const min = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
        const prev = new Date(vista.getFullYear(), vista.getMonth() - 1, 1);
        if (prev >= min) {
            vista = prev;
            renderCalendario();
        }
    });

    raiz.querySelector("[data-cal-next]").addEventListener("click", () => {
        vista = new Date(vista.getFullYear(), vista.getMonth() + 1, 1);
        renderCalendario();
    });

    async function renderCalendario() {
        if (!estado.modalidad) return;
        titulo.textContent = `${MESES[vista.getMonth()]} ${vista.getFullYear()}`;
        vaciar(contenedorDias);

        const primero = new Date(vista.getFullYear(), vista.getMonth(), 1);
        const ultimo = new Date(vista.getFullYear(), vista.getMonth() + 1, 0);

        let disponibles = new Set();
        try {
            const params = new URLSearchParams({ modalidad: estado.modalidad, desde: iso(primero), hasta: iso(ultimo) });
            const res = await fetch(`${urls.dias}?${params}`, { headers: { Accept: "application/json" } });
            const data = await res.json();
            disponibles = new Set(data.dias || []);
        } catch (e) { /* sin conexión */ }

        const offset = (primero.getDay() + 6) % 7;
        for (let i = 0; i < offset; i++) {
            const hueco = document.createElement("span");
            hueco.className = "pp-cal__dia is-vacio";
            contenedorDias.appendChild(hueco);
        }

        for (let d = 1; d <= ultimo.getDate(); d++) {
            const fecha = new Date(vista.getFullYear(), vista.getMonth(), d);
            const fiso = iso(fecha);
            const cel = document.createElement("button");
            cel.type = "button";
            cel.className = "pp-cal__dia";
            cel.textContent = String(d);

            if (disponibles.has(fiso) && fiso >= hoyIso) {
                if (fiso === estado.fecha) cel.classList.add("is-sel");
                cel.addEventListener("click", () => seleccionarDia(fiso, cel));
            } else {
                cel.disabled = true;
                cel.classList.add("is-off");
            }
            contenedorDias.appendChild(cel);
        }
    }

    function seleccionarDia(fiso, cel) {
        estado.fecha = fiso;
        estado.hora = null;
        contenedorDias.querySelectorAll(".pp-cal__dia").forEach((c) => c.classList.remove("is-sel"));
        cel.classList.add("is-sel");
        form.hidden = true;
        cargarHoras(fiso);
    }

    async function cargarHoras(fiso) {
        pasoHora.hidden = false;
        vaciar(contenedorHoras);
        const cargando = document.createElement("p");
        cargando.className = "pp-reserva__nota";
        cargando.textContent = "Cargando horarios...";
        contenedorHoras.appendChild(cargando);

        let huecos = [];
        try {
            const params = new URLSearchParams({ modalidad: estado.modalidad, fecha: fiso });
            const res = await fetch(`${urls.horas}?${params}`, { headers: { Accept: "application/json" } });
            const data = await res.json();
            huecos = data.huecos || [];
        } catch (e) { /* sin conexión */ }

        vaciar(contenedorHoras);
        if (!huecos.length) {
            const vacio = document.createElement("p");
            vacio.className = "pp-reserva__nota";
            vacio.textContent = "No quedan horarios libres ese día.";
            contenedorHoras.appendChild(vacio);
            return;
        }

        huecos.forEach((h) => {
            const boton = document.createElement("button");
            boton.type = "button";
            boton.className = "pp-reserva__hora";
            boton.textContent = h.inicio;
            boton.addEventListener("click", () => {
                estado.hora = h.inicio;
                contenedorHoras.querySelectorAll(".pp-reserva__hora").forEach((x) => x.classList.toggle("is-sel", x === boton));
                abrirForm();
            });
            contenedorHoras.appendChild(boton);
        });
    }

    function abrirForm() {
        form.hidden = false;
        form.querySelector("[data-f-modalidad]").value = estado.modalidad;
        form.querySelector("[data-f-fecha]").value = estado.fecha;
        form.querySelector("[data-f-hora]").value = estado.hora;
        errorBox.hidden = true;
    }

    const telInput = form.querySelector('[name="telefono"]');
    telInput.addEventListener("input", () => {
        telInput.value = telInput.value.replace(/[^\d\s]/g, "");
    });

    form.addEventListener("submit", async (evento) => {
        evento.preventDefault();
        errorBox.hidden = true;
        submitBtn.disabled = true;

        try {
            const res = await fetch(urls.store, {
                method: "POST",
                headers: { "X-CSRF-TOKEN": csrf, Accept: "application/json" },
                body: new FormData(form),
            });
            const data = await res.json();

            if (!res.ok) {
                mostrarError(data.mensaje || "No se pudo completar la reserva. Revisa los datos.");
                if (res.status === 422 && estado.fecha) cargarHoras(estado.fecha);
                submitBtn.disabled = false;
                return;
            }
            exito(data);
        } catch (err) {
            mostrarError("Ha ocurrido un error de conexión. Inténtalo de nuevo.");
            submitBtn.disabled = false;
        }
    });

    function mostrarError(mensaje) {
        errorBox.textContent = mensaje;
        errorBox.hidden = false;
    }

    function exito(data) {
        submitBtn.disabled = false;
        if (modal) {
            if (resumen) resumen.textContent = `${data.cita.modalidad} · ${data.cita.fecha} a las ${data.cita.hora}.`;
            if (googleBtn) googleBtn.href = data.google_url;
            modal.hidden = false;
            document.body.style.overflow = "hidden";
        }
        form.reset();
        form.hidden = true;
        pasoHora.hidden = true;
        estado.fecha = null;
        estado.hora = null;
        renderCalendario();
    }

    if (modal) {
        modal.querySelectorAll("[data-reserva-close]").forEach((el) => {
            el.addEventListener("click", () => {
                modal.hidden = true;
                document.body.style.overflow = "";
            });
        });
    }
})();

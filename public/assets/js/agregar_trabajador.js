document.addEventListener("DOMContentLoaded", () => {

    const modal      = document.getElementById("modalAgregar");
    const btnAbrir    = document.getElementById("btnAgregarTrabajador");
    const btnCancelar = document.getElementById("btnCancelarAgregar");
    const form        = document.getElementById("formAgregarTrabajador");
    const errorTxt    = document.getElementById("modalError");

    if (!modal || !btnAbrir || !form) return;

    btnAbrir.addEventListener("click", () => {
        errorTxt.textContent = "";
        form.reset();
        modal.hidden = false;
    });

    btnCancelar.addEventListener("click", cerrarModal);

    // Cerrar al hacer clic fuera de la caja del modal
    modal.addEventListener("click", (e) => {
        if (e.target === modal) cerrarModal();
    });

    function cerrarModal() {
        modal.hidden = true;
    }

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        errorTxt.textContent = "";

        const datos      = new FormData(form);
        const btnGuardar = form.querySelector(".btn-guardar");
        btnGuardar.disabled = true;
        btnGuardar.textContent = "Guardando...";

        fetch("/trabajadores", { method: "POST", body: datos })
            .then(res => res.json())
            .then(data => {
                if (!data.ok) {
                    errorTxt.textContent = data.error || "No se pudo guardar";
                    return;
                }
                agregarFilaATabla(data.trabajador);
                cerrarModal();
            })
            .catch(() => {
                errorTxt.textContent = "Error de conexión al guardar";
            })
            .finally(() => {
                btnGuardar.disabled = false;
                btnGuardar.textContent = "Guardar";
            });
    });

    function agregarFilaATabla(t) {
        // Encuentra el sub-contenedor (Administrativo o Docente) por su título
        let tbody = null;
        document.querySelectorAll(".sub-contenedor").forEach(cont => {
            const titulo = cont.querySelector("h1")?.textContent.trim();
            if (titulo === t.Tipo_personal) {
                tbody = cont.querySelector("tbody");
            }
        });
        if (!tbody) return;

        const fila = document.createElement("tr");
        fila.dataset.id = t.Id_Trabajador;

        [
            ["Folio", t.Folio],
            ["Nombre_completo", t.Nombre_completo],
            ["Numero_plaza", t.Numero_plaza],
            ["Marca_automovil", t.Marca_automovil],
            ["Placa", t.Placa],
        ].forEach(([campo, valor]) => {
            const td = document.createElement("td");
            td.dataset.campo = campo;
            td.textContent = valor;
            fila.appendChild(td);
        });

        // Celda del switch de vigencia (los trabajadores nuevos siempre inician activos)
        const tdVigencia = document.createElement("td");
        tdVigencia.className = "col-vigencia";
        const label = document.createElement("label");
        label.className = "switch-vigencia";
        const toggle = document.createElement("input");
        toggle.type = "checkbox";
        toggle.className = "vigencia-toggle";
        toggle.dataset.id = t.Id_Trabajador;
        toggle.checked = true;
        const slider = document.createElement("span");
        slider.className = "switch-slider";
        label.appendChild(toggle);
        label.appendChild(slider);
        tdVigencia.appendChild(label);
        fila.appendChild(tdVigencia);

        const tdCheck = document.createElement("td");
        const check = document.createElement("input");
        check.type = "checkbox";
        check.className = "trabajador-check";
        check.dataset.nombre = t.Nombre_completo;
        check.dataset.numero = t.Numero_plaza;
        check.dataset.marca  = t.Marca_automovil;
        check.dataset.placa  = t.Placa;
        check.dataset.tipo   = t.Tipo_personal;
        tdCheck.appendChild(check);
        fila.appendChild(tdCheck);

        tbody.appendChild(fila);

        // Si el filtro "Inactivos" está activo, la fila nueva (activa) se oculta
        const filtroActivo = document.querySelector(".btn-filtro.activo");
        if (filtroActivo && filtroActivo.dataset.filtro === "no") {
            fila.style.display = "none";
        }
    }

});
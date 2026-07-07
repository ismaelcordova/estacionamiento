document.addEventListener("DOMContentLoaded", () => {

    const mapaDataset = {
        Nombre_completo:  "nombre",
        Numero_plaza:     "numero",
        Marca_automovil:  "marca",
        Placa:            "placa",
    };

    let ultimoToque = { celda: null, tiempo: 0 };

    // Doble clic (PC)
    document.addEventListener("dblclick", (e) => {
        const celda = e.target.closest("td[data-campo]");
        if (celda) iniciarEdicion(celda);
    });

    // Doble toque (móvil) — dblclick no siempre se dispara en touch
    document.addEventListener("touchend", (e) => {
        const celda = e.target.closest("td[data-campo]");
        if (!celda) return;

        const ahora = Date.now();
        if (ultimoToque.celda === celda && (ahora - ultimoToque.tiempo) < 350) {
            e.preventDefault();
            iniciarEdicion(celda);
            ultimoToque = { celda: null, tiempo: 0 };
        } else {
            ultimoToque = { celda, tiempo: ahora };
        }
    });

    function iniciarEdicion(celda) {
        if (celda.querySelector("input")) return; // ya se está editando

        const valorOriginal = celda.textContent.trim();
        const input = document.createElement("input");
        input.type = "text";
        input.className = "input-edicion";
        input.value = valorOriginal;

        celda.textContent = "";
        celda.appendChild(input);
        input.focus();
        input.select();

        let cancelado = false;

        input.addEventListener("keydown", (ev) => {
            if (ev.key === "Enter") {
                ev.preventDefault();
                input.blur();
            }
            if (ev.key === "Escape") {
                cancelado = true;
                input.blur();
            }
        });

        input.addEventListener("blur", () => {
            const nuevoValor = input.value.trim();

            if (cancelado || nuevoValor === "" || nuevoValor === valorOriginal) {
                celda.textContent = valorOriginal;
                return;
            }

            guardarCambio(celda, nuevoValor, valorOriginal);
        });
    }

    function guardarCambio(celda, nuevoValor, valorOriginal) {
        const fila  = celda.closest("tr");
        const id    = fila.dataset.id;
        const campo = celda.dataset.campo;

        celda.textContent = "Guardando...";

        const body = new URLSearchParams({ id, campo, valor: nuevoValor });

        fetch("/trabajadores/campo", { method: "POST", body })
            .then(res => res.json())
            .then(data => {
                if (!data.ok) {
                    celda.textContent = valorOriginal;
                    alert(data.error || "No se pudo guardar el cambio");
                    return;
                }

                celda.textContent = nuevoValor;

                // Mantiene sincronizados los data-* del checkbox (usados al generar pases)
                const check = fila.querySelector(".trabajador-check");
                const atributo = mapaDataset[campo];
                if (check && atributo) {
                    check.dataset[atributo] = nuevoValor;
                }
            })
            .catch(() => {
                celda.textContent = valorOriginal;
                alert("Error de conexión al guardar el cambio");
            });
    }

});
document.addEventListener("DOMContentLoaded", () => {

    const input = document.getElementById("buscarInput");
    if (!input) return;

    input.addEventListener("input", function () {
        const q = this.value.trim().toLowerCase();

        document.querySelectorAll(".sub-contenedor").forEach(contenedor => {
            let hayResultados = false;

            contenedor.querySelectorAll("tbody tr").forEach(fila => {
                const check  = fila.querySelector(".trabajador-check");
                const textos = [
                    ...Array.from(fila.querySelectorAll("td")).map(td => td.textContent),
                    check?.dataset.nombre ?? '',
                    check?.dataset.numero ?? '',
                    check?.dataset.marca  ?? '',
                    check?.dataset.placa  ?? '',
                ].join(" ").toLowerCase();

                const coincide = q === "" || textos.includes(q);
                fila.style.display = coincide ? "" : "none";
                if (coincide) hayResultados = true;
            });

            let aviso = contenedor.querySelector(".sin-resultados");
            if (!aviso) {
                aviso = document.createElement("p");
                aviso.className = "sin-resultados";
                aviso.textContent = "Sin resultados";
                contenedor.appendChild(aviso);
            }
            aviso.style.display = (!hayResultados && q !== "") ? "block" : "none";
        });
    });

});
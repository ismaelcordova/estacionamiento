document.addEventListener("DOMContentLoaded", () => {

    const botones = document.querySelectorAll(".btn-filtro");
    if (!botones.length) return;

    botones.forEach(btn => {
        btn.addEventListener("click", function () {
            botones.forEach(b => b.classList.remove("activo"));
            this.classList.add("activo");
            aplicarFiltro(this.dataset.filtro);
        });
    });

    // ← Esto dispara el filtro inicial según el botón que ya tiene la clase "activo"
    const activo = document.querySelector(".btn-filtro.activo");
    if (activo) aplicarFiltro(activo.dataset.filtro);

    function aplicarFiltro(filtro) {
        document.querySelectorAll("tbody tr").forEach(fila => {
            if (filtro === "todos") {
                fila.style.display = "";
                return;
            }
            const esInactiva = fila.classList.contains("fila-inactiva");
            if (filtro === "no") {
                fila.style.display = esInactiva ? "" : "none";
            } else {
                fila.style.display = esInactiva ? "none" : "";
            }
        });
    }

});
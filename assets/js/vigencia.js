document.addEventListener("DOMContentLoaded", () => {

    document.addEventListener("change", (e) => {
        const toggle = e.target.closest(".vigencia-toggle");
        if (!toggle) return;

        const fila = toggle.closest("tr");
        const id   = toggle.dataset.id;
        const vigente = toggle.checked ? 1 : 0;

        toggle.disabled = true;

        const body = new URLSearchParams({ id, vigente });

        fetch("./config/actualizar_vigencia.php", { method: "POST", body })
            .then(res => res.json())
            .then(data => {
                if (!data.ok) {
                    toggle.checked = !toggle.checked; // revertir el switch
                    alert(data.error || "No se pudo actualizar la vigencia");
                    return;
                }

                fila.classList.toggle("fila-inactiva", !toggle.checked);
                reaplicarFiltro(fila);
            })
            .catch(() => {
                toggle.checked = !toggle.checked; // revertir el switch
                alert("Error de conexión al actualizar la vigencia");
            })
            .finally(() => {
                toggle.disabled = false;
            });
    });

    // Mantiene la fila coherente con el filtro (Todos / Activos / Inactivos) activo
    function reaplicarFiltro(fila) {
        const filtroActivo = document.querySelector(".btn-filtro.activo");
        if (!filtroActivo || filtroActivo.dataset.filtro === "todos") return;

        const esInactiva = fila.classList.contains("fila-inactiva");

        if (filtroActivo.dataset.filtro === "no") {
            fila.style.display = esInactiva ? "" : "none";
        } else {
            fila.style.display = esInactiva ? "none" : "";
        }
    }

});
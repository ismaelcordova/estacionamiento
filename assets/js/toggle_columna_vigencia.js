document.addEventListener("DOMContentLoaded", () => {

    const btn = document.getElementById("btnToggleVigencia");
    if (!btn) return;

    const CLAVE_STORAGE = "estacionamiento_mostrar_vigencia";

    // Recuerda la preferencia del usuario entre visitas
    const mostrar = localStorage.getItem(CLAVE_STORAGE) === "1";
    aplicarEstado(mostrar);

    btn.addEventListener("click", () => {
        const estaVisible = document.body.classList.contains("mostrar-vigencia");
        const nuevoEstado = !estaVisible;
        aplicarEstado(nuevoEstado);
        localStorage.setItem(CLAVE_STORAGE, nuevoEstado ? "1" : "0");
    });

    function aplicarEstado(visible) {
        document.body.classList.toggle("mostrar-vigencia", visible);
        btn.classList.toggle("activo", visible);
    }

});
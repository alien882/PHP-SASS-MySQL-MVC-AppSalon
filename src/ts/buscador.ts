document.addEventListener("DOMContentLoaded", () => {
    iniciarBuscador();
});

function iniciarBuscador() {
    buscarPorFecha();
}

function buscarPorFecha() {
    const fechaInput = document.querySelector("#fecha");
    fechaInput?.addEventListener("input", e => {
        const fechaSeleccionada = e.target as HTMLInputElement;
        window.location.assign(`?fecha=${fechaSeleccionada.value}`);
    });
}

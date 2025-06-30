let paso: number = 1;
const pasoInicial = 1;
const pasoFinal = 3;

//const urlBase = "http://192.168.1.65:3000";
const urlBase = location.origin;

interface Servicio {
    id: string;
    nombre: string;
    precio: string;
}

interface Cita {
    nombre: string;
    fecha: string;
    hora: string;
    servicios: Servicio[];
    usuarioId: string;
}

const cita: Cita = {
    nombre: "",
    fecha: "",
    hora: "",
    servicios: [],
    usuarioId: ""
}

document.addEventListener("DOMContentLoaded", () => {
    iniciarApp();
})

function iniciarApp() {

    mostrarSeccion();
    tabs();
    botonesPaginador();
    paginaSiguiente();
    paginaAnterior();
    consultarAPI();
    nombreCliente();
    seleccionarFecha();
    seleccionarHora();
    idCliente();
}


function tabs() {

    const botones = document.querySelectorAll(".tabs button");

    botones.forEach(boton => {

        boton.addEventListener("click", e => {
            const botonSeleccionado = e.target as HTMLButtonElement;
            paso = Number(botonSeleccionado.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        })
    })
}

function mostrarSeccion() {

    const seccionAnterior = document.querySelector(".mostrar");
    seccionAnterior?.classList.remove("mostrar");

    const seccion = document.querySelector(`#paso-${paso}`);
    seccion?.classList.add("mostrar");

    const tabAnterior = document.querySelector(".actual");
    tabAnterior?.classList.remove("actual");

    const tab = document.querySelector(`[data-paso="${paso}"]`)
    tab?.classList.add("actual");

}

function botonesPaginador() {

    const paginaAnterior = document.querySelector("#anterior");
    const paginaSiguiente = document.querySelector("#siguiente");

    if (paso === 1) {
        paginaSiguiente?.classList.remove("ocultar");
        paginaAnterior?.classList.add("ocultar");
    } else if (paso === 3) {
        paginaAnterior?.classList.remove("ocultar");
        paginaSiguiente?.classList.add("ocultar");
        mostrarResumen();
    } else {
        paginaAnterior?.classList.remove("ocultar");
        paginaSiguiente?.classList.remove("ocultar");
    }

    mostrarSeccion();
}

function paginaSiguiente() {

    const paginaSiguiente = document.querySelector("#siguiente");
    paginaSiguiente?.addEventListener("click", () => {
        if (paso >= pasoFinal) {
            return;
        }

        paso++;
        botonesPaginador();
    });
}

function paginaAnterior() {

    const paginaAnterior = document.querySelector("#anterior");
    paginaAnterior?.addEventListener("click", () => {
        if (paso <= pasoInicial) {
            return;
        }

        paso--;
        botonesPaginador();
    });
}

async function consultarAPI() {
    try {
        const url = `${urlBase}/api/servicios`;
        const resultado = await fetch(url)
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.error(error);
    }
}

function mostrarServicios(servicios: Servicio[]) {

    servicios.forEach(servicio => {

        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement("P");
        nombreServicio.classList.add("nombre-servicio");
        nombreServicio.textContent = nombre

        const precioServicio = document.createElement("P");
        precioServicio.classList.add("precio-servicio");
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement("DIV");
        servicioDiv.classList.add("servicio");
        servicioDiv.dataset.idServicio = id;
        // o tambien
        //servicioDiv.setAttribute("data-id-servicio", id);
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        servicioDiv.onclick = () => seleccionarServicio(servicio);

        const listaServicios = document.querySelector("#servicios");
        listaServicios?.appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio: Servicio) {

    const { servicios } = cita;
    const divServicio = document.querySelector(`[data-id-servicio="${servicio.id}"]`);
    const existeEnArreglo = servicios.some((elemento: Servicio) => elemento.id === servicio.id);

    if (existeEnArreglo) {
        cita.servicios = servicios.filter((elemento: Servicio) => elemento.id !== servicio.id);
        divServicio?.classList.remove("seleccionado");
    } else {
        cita.servicios = [...servicios, servicio];
        divServicio?.classList.add("seleccionado");
    }
}
function nombreCliente() {
    cita.nombre = (document.querySelector("#nombre") as HTMLInputElement).value;
}

function seleccionarFecha() {

    const inputFecha = document.querySelector("#fecha");
    inputFecha?.addEventListener("input", e => {

        const fechaSeleccionada = e.target as HTMLInputElement;
        const dia = new Date(fechaSeleccionada.value).getUTCDay();


        if ([0, 6].includes(dia)) {
            fechaSeleccionada.value = "";
            mostrarAlerta("Fines de semanda no permitidos", "error");
        } else {
            cita.fecha = fechaSeleccionada.value;
        }
    });
}

function mostrarAlerta(mensaje: string, tipo: string, desaparece: boolean = true) {

    const alertaPrevia = document.querySelector(".alerta");
    if (alertaPrevia) {
        alertaPrevia.remove();
    }

    const alerta = document.createElement("DIV");
    alerta.textContent = mensaje;
    alerta.classList.add("alerta");
    alerta.classList.add(tipo);

    const divAlertas = document.querySelector("#alertas");
    divAlertas?.appendChild(alerta);

    if (desaparece) {
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}

function seleccionarHora() {

    const inputHora = document.querySelector("#hora");
    inputHora?.addEventListener("input", e => {

        const horaSeleccionada = e.target as HTMLInputElement;
        const hora = Number(horaSeleccionada.value.split(":")[0]);

        if (hora < 10 || hora > 18) {
            mostrarAlerta("Hora no válida", "error");
            horaSeleccionada.value = "";
        } else {
            cita.hora = horaSeleccionada.value
        }
    });
}

function mostrarResumen() {

    const resumen = document.querySelector(".contenido-resumen");
    const divAlertas = document.querySelector("#alertas");

    while (divAlertas?.firstChild) {
        divAlertas.removeChild(divAlertas.firstChild);
    }

    while (resumen?.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if (Object.values(cita).includes("") || cita.servicios.length === 0) {
        mostrarAlerta("Hacen falta datos", "error", false);
        return;
    }

    const { nombre, fecha, hora, servicios } = cita;

    const headingServicios = document.createElement("H3");
    headingServicios.textContent = "Resumen de Servicios";
    resumen?.appendChild(headingServicios);

    servicios.forEach(servicio => {

        const { id, nombre, precio } = servicio;
        const contenedorServicio = document.createElement("DIV");
        contenedorServicio.classList.add("contenedor-servicio");

        const textoServicio = document.createElement("P");
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement("P");
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen?.appendChild(contenedorServicio);
    });

    const headingCita = document.createElement("H3");
    headingCita.textContent = "Resumen de Cita";
    resumen?.appendChild(headingCita);

    const nombreCliente = document.createElement("P");
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    const fechaArray = fecha.split("-");
    const dia = Number(fechaArray[2]);
    const mes = Number(fechaArray[1]) - 1; // en js el mes inicia del 0 al 11
    const year = Number(fechaArray[0]);

    const fechaObj = new Date(year, mes, dia);
    const fechaFormateada = fechaObj.toLocaleDateString("es-MX", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric"
    });

    const fechaCita = document.createElement("P");
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement("P");
    horaCita.innerHTML = `<span>Hora:</span> ${hora} hrs`;

    const botonReservar = document.createElement("BUTTON");
    botonReservar.classList.add("boton");
    botonReservar.textContent = "Reservar Cita";
    botonReservar.onclick = reservarCita

    resumen?.appendChild(nombreCliente);
    resumen?.appendChild(fechaCita);
    resumen?.appendChild(horaCita);

    resumen?.appendChild(botonReservar);
}

async function reservarCita() {

    //const datos = new FormData();
    // agregar datos al formulario
    //datos.append("nombre", "yon");
    // ver el contenido del formData
    //datos.forEach((valor, llave) => console.log(`${llave}: ${valor}`));

    const { nombre, fecha, hora, servicios, usuarioId } = cita;
    const idsServicios = servicios.map(servicio => servicio.id).toString();

    const datos = new FormData();
    datos.append("usuarioId", usuarioId);
    datos.append("fecha", fecha);
    datos.append("hora", hora);
    datos.append("servicios", idsServicios);

    try {
        const url = `${urlBase}/api/citas`;
        const respuesta = await fetch(url, {
            method: "POST",
            body: datos,
        });
        const resultado = await respuesta.json();

        if (resultado.resultado) {
            alert("Cita creada, Tu cita fue creada correctamente!!!");
            window.location.reload();
        }
    } catch (error) {
        alert("Error, Hubo un error al guardar la cita");
    }
}

function idCliente() {
    cita.usuarioId = (document.querySelector("#usuarioId") as HTMLInputElement).value;
}


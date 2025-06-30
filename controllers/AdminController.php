<?php

namespace Controlador;

use Modelo\AdminCita;
use MVC\Router;

class AdminController
{
    public static function index(Router $router)
    {
        esAdministrador();

        $fecha = $_GET["fecha"] ?? date("Y-m-d");

        $fechaArr = explode("-", $fecha);
        $dia = $fechaArr[2];
        $mes = $fechaArr[1];
        $year = $fechaArr[0];

        if (!checkdate($mes, $dia, $year)) {
            header("location: /404");
        }

        $consulta = "SELECT citas.id, citas.hora, CONCAT(usuarios.nombre, ' ', usuarios.apellido) AS cliente, 
            usuarios.email, usuarios.telefono, servicios.nombre AS servicio, servicios.precio FROM citas 
            LEFT JOIN usuarios ON citas.usuarioId = usuarios.id
            LEFT JOIN citas_servicios ON citas_servicios.citaId = citas.id
            LEFT JOIN servicios ON citas_servicios.servicioId = servicios.id
            WHERE fecha = '$fecha'";

        $router->render("admin/index", [
            "nombre" => $_SESSION["nombre"],
            "citas" => AdminCita::SQL($consulta),
            "fecha" => $fecha
        ]);
    }
}

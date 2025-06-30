<?php

namespace Controlador;

use MVC\Router;

class CitaController
{

    public static function index(Router $router)
    {
        estaAutenticado();

        $router->render("cita/index", [
            "nombre" => $_SESSION["nombre"],
            "usuarioId" => $_SESSION["id"]
        ]);
    }
}

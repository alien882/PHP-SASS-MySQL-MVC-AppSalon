<?php

namespace Controlador;

use Modelo\Servicio;
use MVC\Router;

class ServicioController
{
    public static function index(Router $router)
    {
        esAdministrador();

        $router->render("servicios/index", [
            "nombre" => $_SESSION["nombre"],
            "servicios" => Servicio::all()
        ]);
    }

    public static function crear(Router $router)
    {
        esAdministrador();

        $servicio = new Servicio;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $servicio->sincronizar($_POST);
            $servicio->validar();

            if (empty(Servicio::getAlertas())) {
                $servicio->guardar();
                header("location: /servicios");
            }
        }

        $router->render("servicios/crear", [
            "nombre" => $_SESSION["nombre"],
            "servicio" => $servicio,
            "alertas" => Servicio::getAlertas()
        ]);
    }

    public static function actualizar(Router $router)
    {
        esAdministrador();

        if (!is_numeric($_GET["id"])) {
            return;
        }

        $servicio = Servicio::find($_GET["id"]);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $servicio->sincronizar($_POST);
            $servicio->validar();

            if (empty(Servicio::getAlertas())) {
                $servicio->guardar();
                header("location: /servicios");
            }
        }

        $router->render("servicios/actualizar", [
            "nombre" => $_SESSION["nombre"],
            "servicio" => $servicio,
            "alertas" => Servicio::getAlertas()
        ]);
    }

    public static function eliminar()
    {
        esAdministrador();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header("location: /servicios");
        }
    }
}

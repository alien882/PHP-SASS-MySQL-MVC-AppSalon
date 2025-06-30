<?php

namespace Controlador;

use Modelo\Cita;
use Modelo\CitasServicios;
use Modelo\Servicio;

class APIController
{
    public static function index()
    {
        $servicios = Servicio::all();
        echo json_encode($servicios, JSON_UNESCAPED_UNICODE);
    }

    public static function guardar()
    {
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado["id"];

        $idsServicios = explode(",", $_POST["servicios"]);

        foreach ($idsServicios as $idServicio) {

            $args = [
                "citaId" => $id,
                "servicioId" => $idServicio
            ];

            $citasServicios = new CitasServicios($args);
            $citasServicios->guardar();
        }

        echo json_encode($resultado);
    }

    public static function eliminar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $id = $_POST["id"];
            $cita = Cita::find($id);
            $cita->eliminar();
            header("location: " . $_SERVER["HTTP_REFERER"]);
        }
    }
}

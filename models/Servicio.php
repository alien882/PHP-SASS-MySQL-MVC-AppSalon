<?php

namespace Modelo;

class Servicio extends ClaseBase
{
    protected static $tabla = "servicios";
    protected static $columnasDB = ["id", "nombre", "precio"];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? "";
        $this->nombre = $args["nombre"] ?? "";
        $this->precio = $args["precio"] ?? "";
    }

    public function validar()
    {
        if (!$this->nombre) {
            self::$alertas["error"][] = "El nombre del servicio es obligatorio";
        }

        if (!$this->precio) {
            self::$alertas["error"][] = "El precio del servicio es obligatorio";
        }
    }
}

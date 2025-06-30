<?php

namespace Modelo;

class Cita extends ClaseBase
{
    protected static $tabla = "citas";
    protected static $columnasDB = ["id", "fecha", "hora", "usuarioId"];

    public $id;
    public $fecha;
    public $hora;
    public $usuarioId;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? "";
        $this->fecha = $args["fecha"] ?? "";
        $this->hora = $args["hora"] ?? "";
        $this->usuarioId = $args["usuarioId"] ?? "";
    }
}

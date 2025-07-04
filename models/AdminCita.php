<?php

namespace Modelo;

use Modelo\ClaseBase;

class AdminCita extends ClaseBase
{
    protected static $tabla = "citas_servicios";
    protected static $columnasDB = ["id", "hora", "cliente", "email", "telefono", "servicio", "precio"];

    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? "";
        $this->hora = $args["hora"] ?? "";
        $this->cliente = $args["cliente"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->telefono = $args["telefono"] ?? "";
        $this->servicio = $args["servicio"] ?? "";
        $this->precio = $args["precio"] ?? "";
    }
}

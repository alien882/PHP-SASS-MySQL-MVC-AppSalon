<?php

use Controlador\AdminController;
use Controlador\APIController;
use Controlador\CitaController;
use Controlador\LoginController;
use Controlador\ServicioController;
use MVC\Router;

require_once "../includes/app.php";

$router = new Router();

// iniciar sesion
$router->get("/", [LoginController::class, "login"]);
$router->post("/", [LoginController::class, "login"]);
$router->get("/logout", [LoginController::class, "logout"]);

// recuperar password
$router->get("/olvide-password", [LoginController::class, "olvidePassword"]);
$router->post("/olvide-password", [LoginController::class, "olvidePassword"]);
$router->get("/recuperar-password", [LoginController::class, "recuperarPassword"]);
$router->post("/recuperar-password", [LoginController::class, "recuperarPassword"]);

// crear cuenta
$router->get("/crear-cuenta", [LoginController::class, "registro"]);
$router->post("/crear-cuenta", [LoginController::class, "registro"]);

// confirmar cuenta
$router->get("/confirmar-cuenta", [LoginController::class, "confirmar"]);
$router->get("/mensaje", [LoginController::class, "mensaje"]);


$router->get("/cita", [CitaController::class, "index"]);
$router->get("/admin", [AdminController::class, "index"]);

$router->get("/api/servicios", [APIController::class, "index"]);
$router->post("/api/citas", [APIController::class, "guardar"]);
$router->post("/api/eliminar", [APIController::class, "eliminar"]);

$router->get("/servicios", [ServicioController::class, "index"]);
$router->get("/servicios/crear", [ServicioController::class, "crear"]);
$router->post("/servicios/crear", [ServicioController::class, "crear"]);
$router->get("/servicios/actualizar", [ServicioController::class, "actualizar"]);
$router->post("/servicios/actualizar", [ServicioController::class, "actualizar"]);
$router->post("/servicios/eliminar", [ServicioController::class, "eliminar"]);

$router->comprobarRutas();

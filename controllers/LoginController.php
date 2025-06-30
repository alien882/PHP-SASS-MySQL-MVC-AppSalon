<?php

namespace Controlador;

use Clases\Email;
use Modelo\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = new Usuario($_POST);
            $usuario->validarLogin();

            if (empty(Usuario::getAlertas())) {
                $registro =  Usuario::where("email", $usuario->email);

                if ($registro) {
                    $exito = $registro->comprobarPasswordYConfirmado($usuario->password);

                    if ($exito) {
                        session_start();
                        $_SESSION["id"] = $registro->id;
                        $_SESSION["nombre"] = $registro->nombre . " " . $registro->apellido;
                        $_SESSION["email"] = $registro->email;
                        $_SESSION["login"] = true;

                        if ($registro->admin === "1") {
                            $_SESSION["admin"] = $registro->admin ?? null;
                            header("location: /admin");
                        } else {
                            header("location: /cita");
                        }
                    }
                } else {
                    Usuario::setAlerta("error", "el usuario no encontado");
                }
            }
        }

        $router->render("auth/login", [
            "alertas" => Usuario::getAlertas()
        ]);
    }

    public static function logout()
    {
        $_SESSION = [];
        header("location: /");
    }

    public static function olvidePassword(Router $router)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $auth->validarEmail();

            if (empty(Usuario::getAlertas())) {
                $usuario = Usuario::where("email", $auth->email);

                if ($usuario && $usuario->confirmado === "1") {
                    $usuario->crearToken();
                    $usuario->guardar();
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    Usuario::setAlerta("exito", "Revisa tu email");
                } else {
                    Usuario::setAlerta("error", "El usuario no existe o no está confirmado");
                }
            }
        }

        $router->render("auth/olvidePassword", [
            "alertas" => Usuario::getAlertas()
        ]);
    }

    public static function recuperarPassword(Router $router)
    {
        $token = escaparHtml($_GET["token"] ?? "");

        $error = false;

        if ($token) {
            $usuario = Usuario::where("token", $token);

            if ($usuario) {

                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                    $password = new Usuario($_POST);
                    $password->validarPassword();

                    if (empty(Usuario::getAlertas())) {
                        $usuario->password = $password->password;
                        $usuario->hashPassword();
                        $usuario->token = null;
                        $resultado = $usuario->guardar();

                        if ($resultado) {
                            header("location: /");
                        }
                    }
                }
            } else {
                Usuario::setAlerta("error", "Token no válido");
                $error = true;
            }
        } else {
            Usuario::setAlerta("error", "No se encontró ningún token");
        }

        $router->render("auth/recuperarPassword", [
            "alertas" => Usuario::getAlertas(),
            "error" => $error
        ]);
    }

    public static function registro(Router $router)
    {
        $usuario = new Usuario;
        $alertas = Usuario::getAlertas();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                $registro = $usuario->existe();

                if ($registro) {
                    $alertas = Usuario::getAlertas();
                } else {
                    $usuario->hashPassword();
                    $usuario->crearToken();
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header("location: /mensaje");
                    }
                }
            }
        }

        $router->render("auth/registro", [
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render("auth/mensaje");
    }

    public static function confirmar(Router $router)
    {
        $token = escaparHtml($_GET["token"] ?? "");

        if ($token) {
            $usuario = Usuario::where("token", $token);

            if ($usuario) {
                $usuario->confirmado = 1;
                $usuario->token = "";
                $usuario->guardar();
                Usuario::setAlerta("exito", "Cuenta verificada correctamente");
            } else {
                Usuario::setAlerta("error", "Token no válido");
            }
        } else {
            Usuario::setAlerta("error", "No se encontró ningún token");
        }

        $router->render("auth/confirmar", [
            "alertas" =>  Usuario::getAlertas()
        ]);
    }
}

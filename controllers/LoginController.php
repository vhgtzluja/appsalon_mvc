<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                //Comprobar que existe el usuario
                $usuario = Usuario::where('email', $auth->email);    

                if($usuario) {
                    //Verificar el password                    
                    if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        //Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre." ".$usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionamiento si es admin o cliente
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        isSession();
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1") {
                    //Generar token
                    $usuario->crearToken();  
                    $usuario->guardar();
                    
                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Alerta de ??xito
                    Usuario::setAlerta('exito', 'E-mail enviado');                    
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no est?? confirmado');                    
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        //Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario) || $usuario->token === '') {
            Usuario::setAlerta('error', 'Token no v??lido');
            $error = true;
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            $usuario->hashPassword();

            if(empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = '';
                
                $resultado = $usuario->guardar();
                if($resultado) {
                    header('Location: /');
                }
            }                
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/restablecer-password', [
            'alertas' => $alertas, 
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {
        $usuario = new Usuario($_POST);

        //Alertas vac??as
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que alertas este vac??o
            if(empty($alertas)) {
                //Verificar que el usuario no este registrado    
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //El usuario no esta registrado y se tiene que almacenar
                    //Hashear el password
                    $usuario->hashPassword();

                    //Generar token ??nico
                    $usuario->crearToken();                    
                    
                    //Grabar el usuario                    
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        //Enviar el e-mail
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarConfirmacion(); 

                        //Redireccionar mensaje creado
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no v??lido');
        } else {
            //Modificar a usuario confirmado
            $usuario->confirmado = 1;
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        //Obtener alertas
        $alertas = Usuario::getAlertas();

        //Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
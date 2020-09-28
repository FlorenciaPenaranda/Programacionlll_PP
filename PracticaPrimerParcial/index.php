<?php

require __DIR__ . '/vendor/autoload.php';

require_once './Entidades/ingreso.php';
require_once './Entidades/registro.php';
require_once './Entidades/precio.php';

use \Firebase\JWT\JWT;


/*
$key = "primerparcial";
$payload = array(
    "iss" => "http://example.org",
    "aud" => "http://example.com",
    "iat" => 1356999524,
    "nbf" => 1357000000
);

/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 *
 *
$jwt = JWT::encode($payload, $key);
print_r($jwt);

$decoded = JWT::decode($jwt, $key, array('HS256'));
die();
*/


//var_dump($_SERVER);


$token = $_SERVER['HTTP_TOKEN'] ?? '';
//var_dump($token);
$key = "primerparcial";
$status = 'loguot';
try {
    $decoded = JWT::decode($token, $key, array('HS256'));
    //print_r($decoded);
    $status = 'logged';
} catch (Throwable $th) {
    echo 'Loggin error!';
}


/** PETICIONES AL SERVIDOR:
 *
 *Metodo _GET: Obtiene recursos
 *Metodo _POST: Crea recursos
 *Metodo _PUT: Modifica recursos
 *Metodo _DELETE: Borra recursos
 */

$method = $_SERVER['REQUEST_METHOD'];
$path_info = $_SERVER['PATH_INFO'];

switch ($path_info) {
    case '/login':
        if ($method == 'POST') {
            $email = $_POST['email'] ?? '';
            $tipoUsuario = $_POST['tipoUsuario'] ?? '';
            $password = $_POST['password'] ?? '';
            $usuario = new registro($email, $tipoUsuario,$password);

            $arrayUsuarios = registro::LeerRegistro();
            $login = $usuario->verificar($arrayUsuarios);

            $payload = array(
                'data' => [
                    'status' => 'logged',
                    'email' => $email,
                    'tipo' => $tipoUsuario
                ]
            );

            if ($login) {
                $jwt = JWT::encode($payload, $key);

                print_r($jwt);

            } else {
                echo ' Debe estar registrado.';
            }
        } else {
            echo 'Método no permitido';
        }
        break;
    case '/ingreso':
        if ($status == 'logged') {
            if ($method == 'POST') {
                $patente = $_POST['patente'] ?? '';
                $timestap = time();
                $fecha_ingreso = date('Y-m-d-H:i:s');
                $tipo =  $_POST['tipo'] ?? '';


                ingreso::guardarIngreso($patente, $fecha_ingreso, $tipo);
            } else if ($method == 'GET') {
                $patente = $_GET['patente'] ?? '';
                $fecha_ingreso = $_GET['fecha_ingreso'] ?? '';
                $tipo =  $_GET['tipo'] ?? '';

                var_dump(ingreso::leerIngreso());
            } else {
                echo 'Método no permitido';
            }
        } else {
            echo 'debe estar logeuado';
        }
        break;

    case '/registro':
        if ($status == 'logged') {
            if ($method == 'POST') {
                $email = $_POST['email'] ?? '';
                $tipoUsuario = $_POST['tipoUsuario'] ?? '';
                $password = $_POST['password'] ?? '';                    


                registro::guardarRegistro($email, $tipoUsuario,$password);
            } else if ($method == 'GET') {
                $email = $_GET['email'] ?? '';
                $tipoUsuario = $_GET['tipoUsuario'] ?? '';
                $password = $_GET['password'] ?? '';

                var_dump(registro::LeerRegistro());
            } else {
                echo 'Método no permitido';
            }
        } else {
            echo 'debe estar logeuado';
        }
        break;

    case '/precio':
        if ($status == 'logged') {
            if ($method == 'POST') {
                $hora = $_POST['hora'] ?? '';
                $estadia = $_POST['estadia'] ?? ''; 
                $mensual = $_POST['mensual'] ?? '';

                Precio::guardarprecio($hora, $estadia, $mensual);
            } else if ($method == 'GET') {
                $hora = $_GET['hora'] ?? '';
                $estadia = $_GET['estadia'] ?? ''; 
                $mensual = $_GET['mensual'] ?? '';

                var_dump(precio::LeerPrecio());
            } else {
                echo 'Método no permitido';
            }
        } else {
            echo 'Debe estar logeuado';
        }
        break;
    

    default:
        echo 'Path erroneo';
        break;
}

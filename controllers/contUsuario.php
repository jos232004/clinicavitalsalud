<?php
if (session_status() !== PHP_SESSION_ACTIVE) { // Verificar si la sesión ya está activa antes de iniciar una nueva
    session_start();
}
//IMPORTANTE: Primero cargamos la conexión, luego el modelo
require_once("../config/conexion.php");
require_once("../models/UsuarioModel.php");

$proceso = isset($_POST['proceso']) ? $_POST['proceso'] : null;

//SEGURIDAD: Si alguien intenta LISTAR/REGISTRAR sin iniciar sesión, lo bloqueamos
if ($proceso !== "LOGIN") {
    if (!isset($_SESSION['idusuario'])) {
        echo "Acceso denegado";
        exit();
    }
}

controlador($proceso);

function controlador($proceso)
{
    $objUsu = new UsuarioModel();

    switch ($proceso) {

        case "LOGIN":

            $email = $_POST['email'];
            $clave = $_POST['clave'];
            $correcto = 0;

            // Debug logging
            error_log("Login attempt: email=$email");

            // Verificar las credenciales del usuario utilizando el modelo
            $resultado = $objUsu->verificarUsuario($email, $clave);

            // Debug: verificar si $resultado es false
            if ($resultado === false) {
                error_log("verificarUsuario retornó false para email: $email");
            } else {
                error_log("verificarUsuario retornó datos para email: $email");
            }

            // Si se encuentra un usuario con las credenciales proporcionadas, se inicia la sesión
            if ($resultado) {
                $correcto = 1;
                // Obtener los datos del usuario y almacenarlos en la sesión
                $_SESSION['idusuario'] = $resultado['id'];
                $_SESSION['nombre'] = $resultado['nombre'];
                $_SESSION['rol'] = $resultado['rol'];
                
                error_log("Sesión iniciada para usuario: {$resultado['email']}");
            }
            echo $correcto;

            break;

        // Otros casos para CRUD de usuarios podrían agregarse aquí

        case "LISTAR":
            $usuarios = $objUsu->listarUsuarios();
            echo json_encode($usuarios);
            break;

        case "REGISTRAR":
            $nombre   = trim($_POST['nombre']);
            $apellido = trim($_POST['apellido']);
            $email    = trim($_POST['email']);
            $clave    = trim($_POST['clave']);
            $rol      = $_POST['rol'];

            // Validaciones backend
            if (empty($nombre) || empty($apellido) || empty($email) || empty($clave)) {
                echo "Campos vacíos";
                exit();
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Email inválido";
                exit();
            }
            if ($objUsu->existeEmail($email)) {
                echo "Email duplicado";
                exit();
            }

            $hash = password_hash($clave, PASSWORD_BCRYPT);
            $resultado = $objUsu->registrarUsuario($nombre, $apellido, $email, $hash, $rol);
            echo $resultado ? 1 : 0;
            break;



        case "EDITAR":
            $id       = $_POST['id'];
            $nombre   = trim($_POST['nombre']);
            $apellido = trim($_POST['apellido']);
            $email    = trim($_POST['email']);
            $rol      = $_POST['rol'];
            $activo   = $_POST['activo'];

            if (empty($nombre) || empty($apellido) || empty($email)) {
                echo "Campos vacíos";
                exit();
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Email inválido";
                exit();
            }
            if ($objUsu->existeEmail($email, $id)) {
                echo "Email duplicado";
                exit();
            }

            $resultado = $objUsu->editarUsuario($id, $nombre, $apellido, $email, $rol, $activo);
            echo $resultado ? 1 : 0;
            break;




        case "ELIMINAR":
            $id = $_POST['id'];
            $resultado = $objUsu->eliminarUsuario($id);
            echo $resultado ? 1 : 0;
            break;

        default:
            echo "No se ha definido proceso: " . $proceso;
    }
}

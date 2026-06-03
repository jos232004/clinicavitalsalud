<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['idusuario'])) {
    echo "Acceso denegado";
    exit(); // Detiene por completo cualquier intento de ver o alterar datos
}

//Cargamos la conexión, luego el modelo
require_once("../config/conexion.php");
require_once("../models/EspecialidadModel.php");

$proceso = isset($_POST['proceso']) ? $_POST['proceso'] : null;
controlador($proceso);

function controlador($proceso)
{
    $objEsp = new EspecialidadModel();

    switch ($proceso) {
        case "LISTAR":
            $especialidades = $objEsp->listarEspecialidades();
            echo json_encode($especialidades);
            break;

        case "REGISTRAR":
            $nombre      = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);

            if (empty($nombre)) {
                echo "Campos vacíos";
                exit();
            }
            if ($objEsp->existeNombre($nombre)) {
                echo "Nombre duplicado";
                exit();
            }

            $resultado = $objEsp->registrarEspecialidad($nombre, $descripcion);
            echo $resultado ? 1 : 0;
            break;

        case "EDITAR":
            $id          = $_POST['id'];
            $nombre      = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);

            if (empty($nombre)) {
                echo "Campos vacíos";
                exit();
            }
            if ($objEsp->existeNombre($nombre, $id)) {
                echo "Nombre duplicado";
                exit();
            }

            $resultado = $objEsp->editarEspecialidad($id, $nombre, $descripcion);
            echo $resultado ? 1 : 0;
            break;

        case "ELIMINAR":
            $id = $_POST['id'];
            $resultado = $objEsp->eliminarEspecialidad($id);
            echo $resultado ? 1 : 0;
            break;

        default:
            echo "Proceso no definido";
    }
}

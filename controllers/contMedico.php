<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['idusuario'])) {
    echo "Acceso denegado";
    exit(); // Detiene cualquier intento de ver o alterar datos sin login
}

// Cargamos la conexión y el modelo
require_once("../config/conexion.php");
require_once("../models/MedicoModel.php");

$proceso = isset($_POST['proceso']) ? $_POST['proceso'] : null;
controlador($proceso);

function controlador($proceso)
{
    $objMed = new MedicoModel();

    switch ($proceso) {
        case "LISTAR":
            $medicos = $objMed->listarMedicos();
            echo json_encode($medicos);
            break;

        case "REGISTRAR":
            $especialidad_id = $_POST['especialidad_id'];
            $nombres   = trim($_POST['nombres']);
            $apellidos = trim($_POST['apellidos']);
            $dni       = !empty(trim($_POST['dni'])) ? trim($_POST['dni']) : null;
            $telefono  = trim($_POST['telefono']);
            $email     = trim($_POST['email']);
            $cmp       = trim($_POST['cmp']);
            /*
            if (empty($nombres) || empty($apellidos) || empty($dni)) {
                echo "Campos vacíos";
                exit();
            }
            if (!preg_match('/^[0-9]{8}$/', $dni)) {
                echo "DNI inválido";
                exit();
            }
            if ($objMed->existeDni($dni)) {
                echo "DNI duplicado";
                exit();
            }*/

            $resultado = $objMed->registrarMedico($especialidad_id, $nombres, $apellidos, $dni, $telefono, $email, $cmp);
            echo $resultado ? 1 : 0;
            break;

        case "EDITAR":
            $id             = $_POST['id'];
            $especialidad_id = $_POST['especialidad_id'];
            $nombres   = trim($_POST['nombres']);
            $apellidos = trim($_POST['apellidos']);
            $dni       = !empty(trim($_POST['dni'])) ? trim($_POST['dni']) : null;
            $telefono  = trim($_POST['telefono']);
            $email     = trim($_POST['email']);
            $cmp       = trim($_POST['cmp']);
            /*
            if (empty($nombres) || empty($apellidos) || empty($dni)) {
                echo "Campos vacíos";
                exit();
            }
            if (!preg_match('/^[0-9]{8}$/', $dni)) {
                echo "DNI inválido";
                exit();
            }
            if ($objMed->existeDni($dni, $id)) {
                echo "DNI duplicado";
                exit();
            }*/

            $resultado = $objMed->editarMedico($id, $especialidad_id, $nombres, $apellidos, $dni, $telefono, $email, $cmp);
            echo $resultado ? 1 : 0;
            break;

        case "ELIMINAR":
            $id = $_POST['id'];
            $resultado = $objMed->eliminarMedico($id);
            echo $resultado ? 1 : 0;
            break;

        // NUEVO CASO: Actualizar estado activo/inactivo
        case "ACTUALIZAR_ESTADO":
            $id = $_POST['id'];
            $estado = $_POST['estado']; // Recibe 1 o 0
            $resultado = $objMed->actualizarEstadoMedico($id, $estado);
            echo $resultado ? 1 : 0;
            break;


        //Nuevo caso
        case "LISTAR_POR_ESPECIALIDAD":
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            $especialidad_id = isset($_POST['especialidad_id']) ? $_POST['especialidad_id'] : '';

            if (!empty($especialidad_id)) {
                $medicos = $objMed->listarMedicosPorEspecialidad($especialidad_id);
                echo json_encode($medicos);
            } else {
                echo json_encode([]);
            }
            exit();
            break;

        default:
            echo "Proceso no definido";
    }
}

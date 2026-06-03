<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['idusuario'])) {
    echo "Acceso denegado";
    exit();
}

require_once("../config/conexion.php");
require_once("../models/DashboardModel.php");

$proceso = isset($_POST['proceso']) ? $_POST['proceso'] : null;
controlador($proceso);

function controlador($proceso)
{
    $objDashboard = new DashboardModel();

    switch ($proceso) {
        case "CARGAR_DATOS":
            // Consolidamos toda la data en un solo viaje AJAX para que cargue volando
            $tarjetas = $objDashboard->getTarjetasDiarias();
            $mensual  = $objDashboard->getHistorialMensual();
            $especialidades = $objDashboard->getDemandaEspecialidades();

            $respuesta = [
                'tarjetas' => $tarjetas,
                'mensual' => $mensual,
                'especialidades' => $especialidades
            ];

            echo json_encode($respuesta);
            break;

        default:
            echo "Proceso no definido";
    }
}

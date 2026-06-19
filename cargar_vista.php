<?php
// cargar_vista.php
session_start();

// Si no hay sesión válida, cortamos el flujo inmediatamente
if (!isset($_SESSION['idusuario'])) {
    echo "<h3>Sesión expirada. Por favor, vuelva a iniciar sesión.</h3>";
    exit();
}

$rol_actual = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'admision';

// 1. Agregamos 'historias_clinicas' que faltaba en tu lista general
$vistas_permitidas = [
    'dashboard',
    'arqueos',
    'usuarios',
    'medicos',
    'citas',
    'reportes',
    'pacientes',
    'especialidad',
    'reporte_citas',
    'historias_clinicas'
];

if (isset($_POST['view'])) {
    $view = $_POST['view'];

    // 2. Validamos que la vista exista en la lista blanca general
    if (in_array($view, $vistas_permitidas)) {

        // --- SEGUNDO CANDADO: CONTROL DE PERMISOS POR ROL ---
        $acceso_concedido = true;

        if ($rol_actual === 'medico') {
            // El médico SOLO puede ver 'historias_clinicas'
            if ($view !== 'historias_clinicas') {
                $acceso_concedido = false;
            }
        } elseif ($rol_actual === 'admision') {
            // Admisión tiene control total MENOS 'usuarios'
            if ($view === 'usuarios') {
                $acceso_concedido = false;
            }
        } // El 'admin' no entra en condiciones porque tiene acceso total implícito

        // 3. Procesamos la carga de la vista según el resultado del candado
        if ($acceso_concedido) {
            $ruta = "views/" . $view . ".php";
            if (file_exists($ruta)) {
                include($ruta);
            } else {
                echo "<h3>Error: El archivo de la vista no existe.</h3>";
            }
        } else {
            echo "<div class='alert alert-danger'><h4><i class='fas fa-exclamation-triangle'></i> Acceso denegado: No tienes permisos para ver este módulo.</h4></div>";
        }
    } else {
        echo "<h3>Vista no permitida.</h3>";
    }
} else {
    // Si entran directo al archivo sin POST, verificamos el destino inicial correcto
    if ($rol_actual === 'medico') {
        include("views/historias_clinicas.php");
    } else {
        include("views/dashboard.php");
    }
}

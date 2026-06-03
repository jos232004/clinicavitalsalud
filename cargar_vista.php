<?php
// cargar_vista.php
$vistas_permitidas = ['dashboard', 'usuarios', 'medicos', 'citas', 'reportes', 'pacientes', 'especialidad', 'reporte_citas'];

if (isset($_POST['view'])) {
    $view = $_POST['view'];

    if (in_array($view, $vistas_permitidas)) {
        $ruta = "views/" . $view . ".php";
        if (file_exists($ruta)) {
            include($ruta);
        } else {
            echo "<h3>Error: El archivo no existe.</h3>";
        }
    } else {
        echo "<h3>Acceso denegado</h3>";
    }
} else {
    include("views/dashboard.php");
}

<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['idusuario']) || !isset($_SESSION['rol'])) {
    echo "Acceso denegado";
    exit();
}

// Cargamos la conexión y el nuevo modelo estructurado
require_once("../config/conexion.php");
require_once("../models/HistoriaModel.php");

$proceso = isset($_POST['proceso']) ? $_POST['proceso'] : null;
controlador($proceso);

function controlador($proceso)
{
    // Instanciamos el modelo siguiendo tu mismo patrón arquitectónico
    $objHC = new HistoriaModel();

    $id_usuario_sesion = $_SESSION['idusuario'];
    $rol_actual = $_SESSION['rol'];

    switch ($proceso) {

        case "BUSCAR_PACIENTE":
            $criterio = trim($_POST['criterio']);

            if (empty($criterio)) {
                echo "0";
                exit();
            }

            $paciente = $objHC->buscarPacientePorCriterio($criterio);
            if ($paciente) {
                echo json_encode([
                    "status" => "success",
                    "paciente" => [
                        "id"       => $paciente['id'],
                        "nombre"   => $paciente['nombres'],
                        "apellido" => $paciente['apellidos'],
                        "dni"      => $paciente['dni'],
                        "edad"     => $paciente['edad']
                    ]
                ]);
            } else {
                echo "0";
            }
            break;

        case "LISTAR_HISTORIAL":
            $id_paciente = isset($_POST['id_paciente']) ? intval($_POST['id_paciente']) : 0;

            if ($id_paciente === 0) {
                echo json_encode([]);
                exit();
            }

            $resultados = $objHC->listarHistorialPorPaciente($id_paciente);
            echo json_encode($resultados);
            break;

        case "GUARDAR_ADJUNTO":
            $id_paciente  = $_POST['id_paciente'];
            $dni_paciente = $_POST['dni_paciente']; // <-- Recibimos el DNI desde la vista
            $especialidad = $_POST['especialidad'];
            $descripcion  = $_POST['descripcion'];
            $archivo      = $_FILES['archivo'];

            if (!empty($id_paciente) && !empty($descripcion) && !empty($archivo)) {

                // 1. Definimos la ruta raíz y la subcarpeta del paciente basada en su DNI
                $directorio_base = "../uploads/historias/";
                $directorio_paciente = $directorio_base . $dni_paciente . "/";

                // 2. Si la carpeta del paciente no existe, la creamos con permisos de lectura/escritura (0777)
                if (!file_exists($directorio_paciente)) {
                    mkdir($directorio_paciente, 0777, true);
                }

                // 3. Sanitizamos el nombre del archivo original para evitar conflictos
                $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
                $nombre_limpio = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "", $archivo['name']);

                // 4. La ruta final donde se guardará físicamente el archivo
                $ruta_destino = $directorio_paciente . $nombre_limpio;

                if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {

                    // 5. En la Base de Datos guardamos la ruta relativa: "DNI/nombre_archivo.ext"
                    $ruta_db = $dni_paciente . "/" . $nombre_limpio;

                    // Aquí ejecutas tu método del modelo pasando $ruta_db
                    $resultado = $objHC->registrarAdjuntoDigitalizado($id_paciente, $id_usuario_sesion, $especialidad, $descripcion,  $ruta_db);

                    echo "1";
                } else {
                    echo "0"; // Error al mover el archivo
                }
            } else {
                echo "Campos vacíos";//Se agrego historia clinica
            }
            break;



        case "GUARDAR_CONSULTA":
            if ($rol_actual !== 'admin' && $rol_actual !== 'medico') {
                echo "0";
                exit();
            }

            $id_paciente = intval($_POST['id_paciente']);
            $sintomas    = trim($_POST['sintomas']);
            $examen      = trim($_POST['examen']);
            $diagnostico = trim($_POST['diagnostico']);
            $tratamiento = trim($_POST['tratamiento']);

            if (empty($sintomas) || empty($diagnostico) || empty($tratamiento)) {
                echo "Campos vacíos";
                exit();
            }

            $resumen_clinico = "SÍNTOMAS: " . $sintomas . " | DIAGNÓSTICO: " . $diagnostico . " | TRATAMIENTO: " . $tratamiento;

            $resultado = $objHC->registrarConsultaMedica($id_paciente, $id_usuario_sesion, $resumen_clinico, $sintomas, $examen, $diagnostico, $tratamiento);
            echo $resultado ? "1" : "0";
            break;

        default:
            echo "Proceso no definido";
    }
}

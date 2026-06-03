<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['idusuario'])) {
    echo "Acceso denegado";
    exit();
}

require_once("../config/conexion.php");
require_once("../models/CitaModel.php");

$proceso = isset($_POST['proceso']) ? $_POST['proceso'] : null;
controlador($proceso);

function controlador($proceso)
{
    $objCita = new CitaModel();

    switch ($proceso) {

        case "LISTAR_CALENDARIO":
            $citas = $objCita->listarCitasCalendario();
            foreach ($citas as &$c) {
                switch ($c['estado']) {
                    case 'pendiente':
                        $c['backgroundColor'] = '#ffad46';
                        break;
                    case 'confirmada':
                        $c['backgroundColor'] = '#1d7af3';
                        break;
                    case 'atendida':
                        $c['backgroundColor'] = '#31ce7f';
                        break;
                    case 'cancelada':
                        $c['backgroundColor'] = '#f25961';
                        break;
                    default:
                        $c['backgroundColor'] = '#6861ce';
                }
                $c['borderColor'] = $c['backgroundColor'];
            }
            echo json_encode($citas);
            break;

        // Llama al nuevo método del modelo que filtra y oculta 'atendida' y 'cancelada'
        case "LISTAR_TABLA_OPERATIVA":
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            $citasTab = $objCita->listarCitasTablaOperativa();

            // Si tu DataTable necesita la estructura JSON pura con "aaData", lo dejas así:
            $resultados = array(
                "sEcho" => 1,
                "iTotalRecords" => count($citasTab),
                "iTotalDisplayRecords" => count($citasTab),
                "aaData" => $citasTab
            );

            echo json_encode($resultados);
            exit();
            break;

        case "REGISTRAR":
            $paciente_id = $_POST['paciente_id'];
            $medico_id   = $_POST['medico_id'];
            $usuario_id  = $_SESSION['idusuario'];
            $fecha       = $_POST['fecha'];
            $hora        = $_POST['hora'];
            $motivo      = trim($_POST['motivo']);

            if ($paciente_id == "NUEVO") {
                $tipo_doc  = $_POST['tipo_doc'];
                $dni       = trim($_POST['dni']);
                $nombres   = trim($_POST['nombres']);
                $apellidos = trim($_POST['apellidos']);
                $fecha_nac = $_POST['fecha_nac'];
                $telefono  = trim($_POST['telefono']);

                // Si el paciente no trae documento, generamos un código provisional único
                if ($tipo_doc === "SIN_DOC") {
                    $dni = "PROV-" . date("Ymd-His");
                }

                if (empty($dni) || empty($nombres) || empty($apellidos)) {
                    echo "Campos vacíos";
                    exit();
                }

                try {
                    $cnx = Conexion::conectar();
                    // Tip: Asegúrate de que tu tabla 'pacientes' acepte caracteres como 'PROV-...' en la columna 'dni' (Varchar)
                    $sqlPac = "INSERT INTO pacientes (dni, nombres, apellidos, fecha_nac, telefono) 
                               VALUES (:dni, :nombres, :apellidos, :fecha_nac, :telefono)";
                    $stmtPac = $cnx->prepare($sqlPac);
                    $stmtPac->execute([
                        ':dni'       => $dni,
                        ':nombres'   => $nombres,
                        ':apellidos' => $apellidos,
                        ':fecha_nac' => !empty($fecha_nac) ? $fecha_nac : null,
                        ':telefono'  => !empty($telefono) ? $telefono : null
                    ]);

                    $paciente_id = $cnx->lastInsertId();
                } catch (PDOException $e) {
                    echo "Error al registrar paciente dinámico: " . $e->getMessage();
                    exit();
                }
            }

            if (empty($paciente_id) || empty($medico_id) || empty($fecha) || empty($hora)) {
                echo "Campos vacíos";
                exit();
            }

            $resultado = $objCita->registrarCita($paciente_id, $medico_id, $usuario_id, $fecha, $hora, $motivo);
            echo $resultado ? 1 : 0;
            break;

        case "CAMBIAR_ESTADO":
            $cita_id      = $_POST['cita_id'];
            $nuevo_estado = $_POST['estado'];

            if (empty($cita_id) || empty($nuevo_estado)) {
                echo "Campos vacíos";
                exit();
            }

            $resultado = $objCita->actualizarEstadoCita($cita_id, $nuevo_estado);
            echo $resultado ? 1 : 0;
            break;

        // ====================================================================
        // NUEVO PROCESO: ACTUALIZAR MOTIVO DE CONSULTA
        // ====================================================================
        case "ACTUALIZAR_MOTIVO":
            $cita_id = isset($_POST['cita_id']) ? $_POST['cita_id'] : null;
            $motivo  = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';

            if (empty($cita_id)) {
                echo "Campos vacíos";
                exit();
            }

            // Llamamos al método que crearemos en tu CitaModel.php
            $resultado = $objCita->actualizarMotivoCita($cita_id, $motivo);
            echo $resultado ? 1 : 0;
            break;

        //Metodo para reportes
        case "REPORTAR_MES":
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            $periodo = isset($_POST['periodo']) ? $_POST['periodo'] : '';

            if (!empty($periodo)) {
                $partes = explode('-', $periodo);
                $anio = $partes[0];
                $mes = $partes[1];

                $citasRep = $objCita->listarCitasMensualesReporte($anio, $mes);
            } else {
                $citasRep = [];
            }

            // Mantenemos la estructura "aaData" idéntica a tu tabla operativa
            $resultados = array(
                "sEcho" => 1,
                "iTotalRecords" => count($citasRep),
                "iTotalDisplayRecords" => count($citasRep),
                "aaData" => $citasRep
            );

            echo json_encode($resultados);
            exit();
            break;

        default:
            echo "Proceso no definido";
    }
}

<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Control estricto de seguridad basado en tu estructura
if (!isset($_SESSION['idusuario'])) {
    echo "Acceso denegado";
    exit();
}

// Cargamos la conexión y luego el modelo unificado de arqueos
require_once("../config/conexion.php");
require_once("../models/ArqueoModel.php");

$proceso = isset($_POST['proceso']) ? $_POST['proceso'] : null;
controlador($proceso);

function controlador($proceso)
{
    $objArq = new ArqueoModel();

    switch ($proceso) {

        case "LISTAR_FECHA":
            $fecha = isset($_POST['fecha']) ? trim($_POST['fecha']) : date('Y-m-d');
            $registros = $objArq->obtenerArqueoPorFecha($fecha);
            echo json_encode($registros);
            break;

        case "BUSCAR_PACIENTE_LOCAL":
            $dni = trim($_POST['dni']);
            if (empty($dni)) {
                echo "0";
                exit();
            }

            // Busca si ya existe en la tabla pacientes de la clínica
            $paciente = $objArq->buscarPacienteLocal($dni);
            echo $paciente ? json_encode($paciente) : "0";
            break;

        case "REGISTRAR_FILA":
            // 1. Captura de datos básicos obligatorios del arqueo
            $fecha_arqueo         = trim($_POST['fecha_arqueo']);
            $turno                = trim($_POST['turno']); // 'MAÑANA' o 'TARDE'
            $descripcion_servicio = trim($_POST['descripcion_servicio']);
            $metodo_pago          = trim($_POST['metodo_pago']); // 'EFECTIVO','YAPE','TARJETA','TRANSFERENCIA'
            $usuario_id           = $_SESSION['idusuario']; // El usuario que inició sesión

            // Validaciones mínimas de estructura de caja
            if (empty($fecha_arqueo) || empty($turno) || empty($descripcion_servicio)) {
                echo "Campos vacíos";
                exit();
            }

            // 2. Captura de datos opcionales del paciente/médico (pueden ser vacíos si es egreso puro)
            $nombre_paciente      = isset($_POST['nombre_paciente']) ? trim($_POST['nombre_paciente']) : '';
            $dni_paciente         = isset($_POST['dni_paciente']) ? trim($_POST['dni_paciente']) : '';
            $celular_paciente     = isset($_POST['celular_paciente']) ? trim($_POST['celular_paciente']) : '';
            $edad_paciente        = isset($_POST['edad_paciente']) && $_POST['edad_paciente'] !== '' ? intval($_POST['edad_paciente']) : null;
            $tipo_paciente        = isset($_POST['tipo_paciente']) ? trim($_POST['tipo_paciente']) : null; // 'AMBULATORIO', etc.
            $medico_tratante      = isset($_POST['medico_tratante']) ? trim($_POST['medico_tratante']) : '';
            $observaciones        = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';

            // 3. Captura de Especialidades (Checkbox en frontend: envían 1 o 0)
            // NUEVO COMPONENTE AGREGADO: CONSULTAS
            $es_consulta          = isset($_POST['es_consulta']) ? intval($_POST['es_consulta']) : 0;

            $es_laboratorio       = isset($_POST['es_laboratorio']) ? intval($_POST['es_laboratorio']) : 0;
            $es_rayos_x           = isset($_POST['es_rayos_x']) ? intval($_POST['es_rayos_x']) : 0;
            $es_ekg               = isset($_POST['es_ekg']) ? intval($_POST['es_ekg']) : 0;
            $es_riesgo_quirurgico = isset($_POST['es_riesgo_quirurgico']) ? intval($_POST['es_riesgo_quirurgico']) : 0;
            $es_ecografia         = isset($_POST['es_ecografia']) ? intval($_POST['es_ecografia']) : 0;

            // 4. Captura de montos monetarios (se limpian a float)
            $monto_cancelar       = isset($_POST['monto_cancelar']) && $_POST['monto_cancelar'] !== '' ? floatval($_POST['monto_cancelar']) : 0.00;
            $pago_medico_comision = isset($_POST['pago_medico_comision']) && $_POST['pago_medico_comision'] !== '' ? floatval($_POST['pago_medico_comision']) : 0.00;
            $egreso               = isset($_POST['egreso']) && $_POST['egreso'] !== '' ? floatval($_POST['egreso']) : 0.00;

            // 5. INTELIGENCIA DE GUARDADO AUTOMÁTICO EN LA MAESTRA DE PACIENTES
            if (!empty($dni_paciente) && preg_match('/^[0-9]{8}$/', $dni_paciente)) {
                if (!$objArq->existeDniEnPacientes($dni_paciente) && !empty($nombre_paciente)) {

                    // Separamos el string del nombre de forma limpia para cumplir con tu tabla maestra
                    $partes = explode(" ", $nombre_paciente, 2);
                    $nombres = $partes[0];
                    $apellidos = isset($partes[1]) ? $partes[1] : 'S/A';

                    $objArq->registrarPacienteDesdeCaja($dni_paciente, $nombres, $apellidos, $celular_paciente);
                }
            }

            // 6. Empaquetar todo el array asociativo para el modelo
            $datosArqueo = [
                'fecha_arqueo'         => $fecha_arqueo,
                'turno'                => $turno,
                'nombre_paciente'      => $nombre_paciente,
                'dni_paciente'         => $dni_paciente,
                'celular_paciente'     => $celular_paciente,
                'edad_paciente'        => $edad_paciente,
                'tipo_paciente'        => $tipo_paciente,
                'descripcion_servicio' => $descripcion_servicio,
                'medico_tratante'      => $medico_tratante,

                // Mapeo de la nueva especialidad
                'es_consulta'          => $es_consulta,

                'es_laboratorio'       => $es_laboratorio,
                'es_rayos_x'           => $es_rayos_x,
                'es_ekg'               => $es_ekg,
                'es_riesgo_quirurgico' => $es_riesgo_quirurgico,
                'es_ecografia'         => $es_ecografia,
                'monto_cancelar'       => $monto_cancelar,
                'pago_medico_comision' => $pago_medico_comision,
                'egreso'               => $egreso,
                'metodo_pago'          => $metodo_pago,
                'observaciones'        => $observaciones,
                'usuario_id'           => $usuario_id
            ];

            // Guardamos la fila final en la base de datos
            $resultado = $objArq->registrarFilaArqueo($datosArqueo);
            echo $resultado ? 1 : 0;
            break;

        default:
            echo "Proceso no definido";
    }
}

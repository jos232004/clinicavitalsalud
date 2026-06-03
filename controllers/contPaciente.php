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
require_once("../models/PacienteModel.php");


$proceso = isset($_POST['proceso']) ? $_POST['proceso'] : null;
controlador($proceso);

function controlador($proceso)
{
    $objPac = new PacienteModel();

    switch ($proceso) {
        case "LISTAR":
            $pacientes = $objPac->listarPacientes();
            echo json_encode($pacientes);
            break;

        case "REGISTRAR":
            $dni       = trim($_POST['dni']);
            $nombres   = trim($_POST['nombres']);
            $apellidos = trim($_POST['apellidos']);
            $fecha_nac = isset($_POST['fecha_nac']) ? trim($_POST['fecha_nac']) : ''; // <-- Captura de la fecha
            $telefono  = trim($_POST['telefono']);

            if (empty($dni) || empty($nombres) || empty($apellidos)) {
                echo "Campos vacíos";
                exit();
            }
            if (!preg_match('/^[0-9]{8}$/', $dni)) {
                echo "DNI inválido";
                exit();
            }
            if ($objPac->existeDni($dni)) {
                echo "DNI duplicado";
                exit();
            }

            // Enviamos los 5 parámetros en el orden exacto del modelo
            $resultado = $objPac->registrarPaciente($dni, $nombres, $apellidos, $fecha_nac, $telefono);
            echo $resultado ? 1 : 0;
            break;


        case "EDITAR":
            $id        = $_POST['id'];
            $dni       = trim($_POST['dni']);
            $nombres   = trim($_POST['nombres']);
            $apellidos = trim($_POST['apellidos']);
            $fecha_nac = isset($_POST['fecha_nac']) ? trim($_POST['fecha_nac']) : ''; // <-- Captura de la fecha
            $telefono  = trim($_POST['telefono']);

            if (empty($dni) || empty($nombres) || empty($apellidos)) {
                echo "Campos vacíos";
                exit();
            }
            if (!preg_match('/^[0-9]{8}$/', $dni)) {
                echo "DNI inválido";
                exit();
            }
            if ($objPac->existeDni($dni, $id)) {
                echo "DNI duplicado";
                exit();
            }

            // Enviamos los 6 parámetros en el orden exacto del modelo
            $resultado = $objPac->editarPaciente($id, $dni, $nombres, $apellidos, $fecha_nac, $telefono);
            echo $resultado ? 1 : 0;
            break;


        case "ELIMINAR":
            $id = $_POST['id'];
            $resultado = $objPac->eliminarPaciente($id);
            echo $resultado ? 1 : 0;
            break;

        //RECIEN AGREGADO
        case "BUSCAR_DNI":
            $criterio = trim($_POST['dni']);

            if (empty($criterio)) {
                echo "0";
                exit();
            }

            try {
                $cnx = Conexion::conectar();

                // Si es un DNI numérico clásico de 8 dígitos, buscamos uno exacto
                if (preg_match('/^[0-9]{8}$/', $criterio)) {
                    $sql = "SELECT id, dni, nombres, apellidos, fecha_nac, telefono FROM pacientes WHERE dni = :criterio LIMIT 1";
                    $stmt = $cnx->prepare($sql);
                    $stmt->execute([':criterio' => $criterio]);
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Devolvemos el objeto directo o 0
                    echo $resultado ? json_encode($resultado) : "0";
                } else {
                    // Si es texto (Búsqueda por nombres/apellidos o código PROV parcial)
                    $sql = "SELECT id, dni, nombres, apellidos, fecha_nac, telefono 
                            FROM pacientes 
                            WHERE nombres LIKE :crit OR apellidos LIKE :crit OR dni LIKE :crit 
                            LIMIT 10"; // Limitamos a 10 para no saturar

                    $stmt = $cnx->prepare($sql);
                    $stmt->execute([':crit' => "%$criterio%"]);
                    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Si el arreglo está vacío devolvemos 0, si tiene datos devolvemos todo el set
                    echo (!empty($resultados)) ? json_encode($resultados) : "0";
                }
            } catch (PDOException $e) {
                echo "0";
            }
            break;

        default:
            echo "Proceso no definido";
    }
}

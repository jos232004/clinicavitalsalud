<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Control de seguridad
if (!isset($_SESSION['idusuario'])) {
    echo "Acceso denegado";
    exit();
}

// Configurar Zona Horaria antes de jalar la fecha actual
date_default_timezone_set('America/Lima');

require_once("../config/conexion.php");
require_once("../models/ArqueoModel.php");

$fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : date('Y-m-d');

$objArq = new ArqueoModel();
$registros = $objArq->obtenerArqueoPorFecha($fecha);

// Formatear la fecha para el nombre del archivo (Ej: Arqueo_17-06-2026.xls)
$fechaFormato = date("d-m-Y", strtotime($fecha));

// Encabezados HTTP para forzar la descarga en formato Excel (.xls)
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Arqueo_$fechaFormato.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Asegurar que Excel interprete correctamente los caracteres con eñes y tildes
echo "\xEF\xBB\xBF";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .bg-primary {
            background-color: #0d6efd;
            color: white;
        }

        .bg-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .bg-danger {
            background-color: #f8d7da;
            color: #842029;
        }

        .bg-yape {
            background-color: #6f42c1;
            color: white;
        }

        .bg-turno {
            background-color: #ffeb3b;
            font-weight: bold;
            text-align: center;
        }

        border-table {
            border: 1px solid #cccccc;
        }
    </style>
</head>

<body>

    <h2>ARQUEO DE CAJA DIARIO - FECHA: <?php echo $fechaFormato; ?></h2>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr class="bg-primary">
                <th>NOMBRE DE PACIENTE</th>
                <th>CELULAR / DNI</th>
                <th>DESCRIPCION DE SERVICIO</th>
                <th>MEDICO TRATANTE</th>
                <th>CONSULTA</th>
                <th>LAB</th>
                <th>RAYOS X</th>
                <th>EKG</th>
                <th>R.Q</th>
                <th>ECO</th>
                <th>MONTO A CANCELAR</th>
                <th>PAGO MÉDICO</th>
                <th class="bg-yape">YAPE</th>
                <th class="bg-danger">EGRESOS</th>
                <th class="bg-success">INGRESO CLINICA</th>
                <th>OBSERVACIONES</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Inicializadores para los totales del pie de página
            $totConsulta = 0;
            $totLab = 0;
            $totRx = 0;
            $totEkg = 0;
            $totRq = 0;
            $totEco = 0;
            $totMonto = 0;
            $totComision = 0;
            $totYape = 0;
            $totEgreso = 0;
            $totClinica = 0;

            // Agrupamos los registros por turno para crear las filas divisoras dinámicas
            $turnos = ['MAÑANA' => [], 'TARDE' => []];
            foreach ($registros as $r) {
                $turnos[$r['turno']][] = $r;
            }

            foreach ($turnos as $nombreTurno => $filasTurno) {
                if (count($filasTurno) > 0) {
                    // Fila divisora amarilla idéntica a tu plantilla de Excel
                    echo '<tr><td colspan="16" class="bg-turno">TURNO ' . $nombreTurno . '</td></tr>';

                    foreach ($filasTurno as $reg) {
                        $bruto    = floatval($reg['monto_cancelar']);
                        $comision = floatval($reg['pago_medico_comision']);
                        $egreso   = floatval($reg['egreso']);

                        // Lógica exacta solicitada: El ingreso neto de la clínica por fila
                        $netoFilaClinica = $bruto - $comision;

                        // Acumuladores globales
                        $totMonto    += $bruto;
                        $totComision += $comision;
                        $totEgreso   += $egreso;
                        $totClinica  += $netoFilaClinica;

                        // Validar columna YAPE de forma inteligente
                        $montoYape = ($reg['metodo_pago'] === 'YAPE') ? $bruto : 0;
                        if ($reg['metodo_pago'] === 'YAPE') $totYape += $bruto;
            ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reg['nombre_paciente'] ?? 'GASTO DIRECTO'); ?></td>
                            <td>
                                <?php
                                if (!empty($reg['dni_paciente'])) {
                                    echo "DNI: " . $reg['dni_paciente'];
                                    if (!empty($reg['celular_paciente'])) echo " / CELL: " . $reg['celular_paciente'];
                                } else {
                                    echo "--";
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($reg['descripcion_servicio']); ?></td>
                            <td><?php echo htmlspecialchars($reg['medico_tratante'] ?? '--'); ?></td>

                            <td>
                                <?php
                                if (intval($reg['es_consulta']) === 1) {
                                    echo "S/ " . number_format($bruto, 2);
                                    $totConsulta += $bruto;
                                } else {
                                    echo "S/ 0.00";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (intval($reg['es_laboratorio']) === 1) {
                                    echo "S/ " . number_format($bruto, 2);
                                    $totLab += $bruto;
                                } else {
                                    echo "S/ 0.00";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (intval($reg['es_rayos_x']) === 1) {
                                    echo "S/ " . number_format($bruto, 2);
                                    $totRx += $bruto;
                                } else {
                                    echo "S/ 0.00";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (intval($reg['es_ekg']) === 1) {
                                    echo "S/ " . number_format($bruto, 2);
                                    $totEkg += $bruto;
                                } else {
                                    echo "S/ 0.00";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (intval($reg['es_riesgo_quirurgico']) === 1) {
                                    echo "S/ " . number_format($bruto, 2);
                                    $totRq += $bruto;
                                } else {
                                    echo "S/ 0.00";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (intval($reg['es_ecografia']) === 1) {
                                    echo "S/ " . number_format($bruto, 2);
                                    $totEco += $bruto;
                                } else {
                                    echo "S/ 0.00";
                                }
                                ?>
                            </td>

                            <td class="text-right">S/ <?php echo number_format($bruto, 2); ?></td>
                            <td class="text-right">S/ <?php echo number_format($comision, 2); ?></td>
                            <td class="text-right" style="background-color: #f3e5f5;">
                                <?php echo ($montoYape > 0) ? "S/ " . number_format($montoYape, 2) : "-"; ?>
                            </td>
                            <td class="text-right" style="color: red;">
                                <?php echo ($egreso > 0) ? "S/ " . number_format($egreso, 2) : "-"; ?>
                            </td>
                            <td class="text-right font-bold" style="background-color: #e8f5e9; color: #2e7d32;">
                                S/ <?php echo number_format($netoFilaClinica, 2); ?>
                            </td>
                            <td><?php echo htmlspecialchars($reg['observaciones'] ?? ''); ?></td>
                        </tr>
            <?php
                    }
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="font-bold" style="background-color: #f5f5f5;">
                <td colspan="4" class="text-right">TOTALES DEL DÍA:</td>
                <td>S/ <?php echo number_format($totConsulta, 2); ?></td>
                <td>S/ <?php echo number_format($totLab, 2); ?></td>
                <td>S/ <?php echo number_format($totRx, 2); ?></td>
                <td>S/ <?php echo number_format($totEkg, 2); ?></td>
                <td>S/ <?php echo number_format($totRq, 2); ?></td>
                <td>S/ <?php echo number_format($totEco, 2); ?></td>
                <td class="text-right">S/ <?php echo number_format($totMonto, 2); ?></td>
                <td class="text-right">S/ <?php echo number_format($totComision, 2); ?></td>
                <td class="text-right" style="background-color: #e1bee7;">S/ <?php echo number_format($totYape, 2); ?></td>
                <td class="text-right" style="color: red;">S/ <?php echo number_format($totEgreso, 2); ?></td>
                <td class="text-right" style="background-color: #c8e6c9; color: #2e7d32;">S/ <?php echo number_format($totClinica, 2); ?></td>
                <td></td>
            </tr>
            <tr class="font-bold bg-success">
                <td colspan="14" class="text-right">SALDO NETO FINAL EN CAJA CHICA (INGRESOS - EGRESOS):</td>
                <td colspan="2" class="text-center" style="font-size: 14px;">
                    S/ <?php echo number_format(($totClinica - $totEgreso), 2); ?>
                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
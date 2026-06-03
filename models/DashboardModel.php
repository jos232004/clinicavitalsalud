<?php
class DashboardModel
{
    private $cnx;

    public function __construct()
    {
        $this->cnx = Conexion::conectar();
    }

    // 1. Obtener los indicadores numéricos del día actual
    public function getTarjetasDiarias()
    {
        $hoy = date('Y-m-d');
        try {
            // Contadores de citas según estado
            $sqlCitas = "SELECT 
                            COUNT(*) as total,
                            SUM(CASE WHEN estado = 'atendida' THEN 1 ELSE 0 END) as atendidas,
                            SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes
                         FROM citas WHERE fecha = :hoy";
            $stmt = $this->cnx->prepare($sqlCitas);
            $stmt->execute([':hoy' => $hoy]);
            $citas = $stmt->fetch(PDO::FETCH_ASSOC);

            // Contador de pacientes nuevos registrados hoy
            $sqlPacientes = "SELECT COUNT(*) as nuevos FROM pacientes WHERE DATE(created_at) = :hoy";
            $stmtPac = $this->cnx->prepare($sqlPacientes);
            $stmtPac->execute([':hoy' => $hoy]);
            $pac = $stmtPac->fetch(PDO::FETCH_ASSOC);

            return [
                'total_citas' => $citas['total'] ?? 0,
                'atendidas'   => $citas['atendidas'] ?? 0,
                'pendientes'  => $citas['pendientes'] ?? 0,
                'nuevos_pac'  => $pac['nuevos'] ?? 0
            ];
        } catch (PDOException $e) {
            error_log("Error en getTarjetasDiarias: " . $e->getMessage());
            return ['total_citas' => 0, 'atendidas' => 0, 'pendientes' => 0, 'nuevos_pac' => 0];
        }
    }

    // 2. Obtener historial mensual de citas del año corriente (para gráfico de líneas)
    public function getHistorialMensual()
    {
        $anio = date('Y');
        try {
            $sql = "SELECT 
                        MONTH(fecha) as mes, 
                        COUNT(*) as cantidad 
                    FROM citas 
                    WHERE YEAR(fecha) = :anio AND estado != 'cancelada'
                    GROUP BY MONTH(fecha)
                    ORDER BY MONTH(fecha) ASC";
            $stmt = $this->cnx->prepare($sql);
            $stmt->execute([':anio' => $anio]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getHistorialMensual: " . $e->getMessage());
            return [];
        }
    }

    // 3. Obtener demanda por especialidades (para gráfico circular/dona)
    public function getDemandaEspecialidades()
    {
        try {
            $sql = "SELECT 
                        e.nombre as especialidad, 
                        COUNT(c.id) as total_citas
                    FROM citas c
                    INNER JOIN medicos m ON c.medico_id = m.id
                    INNER JOIN especialidades e ON m.especialidad_id = e.id
                    WHERE c.estado != 'cancelada'
                    GROUP BY e.id, e.nombre
                    ORDER BY total_citas DESC 
                    LIMIT 6"; // Top 6 especialidades más solicitadas
            $stmt = $this->cnx->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getDemandaEspecialidades: " . $e->getMessage());
            return [];
        }
    }
}

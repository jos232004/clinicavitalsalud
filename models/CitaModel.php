<?php
class CitaModel
{
    private $cnx;

    public function __construct()
    {
        $this->cnx = Conexion::conectar();
    }

    public function listarCitasCalendario()
    {
        $sql = "SELECT 
                    cita_id AS id,
                    paciente AS title,
                    CONCAT(fecha, 'T', hora) AS start,
                    estado,
                    motivo,
                    paciente_dni, -- Este campo de la vista muestra el documento actual (DNI o Prov)
                    paciente_edad,
                    paciente_telefono,
                    medico,
                    especialidad
                FROM v_citas_detalle";

        $stmt = $this->cnx->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Oculta automáticamente citas cerradas ('atendida', 'cancelada')
    public function listarCitasTablaOperativa()
    {
        $sql = "SELECT 
                    cita_id AS id,
                    paciente AS title,
                    CONCAT(fecha, 'T', hora) AS start,
                    estado,
                    motivo,
                    paciente_dni, -- Este campo de la vista muestra el documento actual (DNI o Prov)
                    paciente_edad,
                    paciente_telefono,
                    medico,
                    especialidad
                FROM v_citas_detalle
                WHERE estado NOT IN ('atendida', 'cancelada')
                ORDER BY fecha ASC, hora ASC";

        $stmt = $this->cnx->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarCita($paciente_id, $medico_id, $usuario_id, $fecha, $hora, $motivo)
    {
        try {
            $sql = "INSERT INTO citas (paciente_id, medico_id, usuario_id, fecha, hora, motivo, estado)
                    VALUES (:paciente_id, :medico_id, :usuario_id, :fecha, :hora, :motivo, 'pendiente')";

            $stmt = $this->cnx->prepare($sql);
            return $stmt->execute([
                ':paciente_id' => $paciente_id,
                ':medico_id'   => $medico_id,
                ':usuario_id'  => $usuario_id,
                ':fecha'       => $fecha,
                ':hora'        => $hora,
                ':motivo'      => !empty($motivo) ? $motivo : null
            ]);
        } catch (PDOException $e) {
            error_log("Error en registrarCita: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstadoCita($cita_id, $nuevo_estado)
    {
        try {
            $sql = "UPDATE citas SET estado = :estado WHERE id = :id";
            $stmt = $this->cnx->prepare($sql);
            return $stmt->execute([
                ':estado' => $nuevo_estado,
                ':id'     => $cita_id
            ]);
        } catch (PDOException $e) {
            error_log("Error en actualizarEstadoCita: " . $e->getMessage());
            return false;
        }
    }

    //Funciones para reportes
    // Método para el reporte mensual de citas
    public function listarCitasMensualesReporte($anio, $mes)
    {
        $sql = "SELECT 
                    cita_id AS id,
                    paciente AS title,
                    CONCAT(fecha, 'T', hora) AS start,
                    estado,
                    motivo,
                    paciente_dni,
                    paciente_telefono,
                    medico,
                    especialidad
                FROM v_citas_detalle
                WHERE YEAR(fecha) = :anio AND MONTH(fecha) = :mes
                ORDER BY fecha DESC, hora DESC";

        $stmt = $this->cnx->prepare($sql);
        $stmt->execute([
            ':anio' => $anio,
            ':mes'  => $mes
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

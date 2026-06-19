<?php
class HistoriaModel
{
    private $cnx;

    public function __construct()
    {
        // Al igual que en PacienteModel, inicializamos la conexión en el atributo privado
        $this->cnx = Conexion::conectar();
    }

    // =========================================================================
    // 1. BUSCAR PACIENTE POR DNI O APELLIDOS
    // =========================================================================
    public function buscarPacientePorCriterio($criterio)
    {
        try {
            // Si es un DNI numérico clásico de 8 dígitos, buscamos coincidencia exacta primero
            if (preg_match('/^[0-9]{8}$/', $criterio)) {
                $sql = "SELECT id, nombres, apellidos, dni, 
                               IF(fecha_nac IS NOT NULL, TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()), 'No registrada') AS edad 
                        FROM pacientes 
                        WHERE dni = :crit LIMIT 1";
                $stmt = $this->cnx->prepare($sql);
                $stmt->execute([':crit' => $criterio]);
            } else {
                // Si es texto, buscamos por aproximación en apellidos o nombres
                $sql = "SELECT id, nombres, apellidos, dni, 
                               IF(fecha_nac IS NOT NULL, TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()), 'No registrada') AS edad 
                        FROM pacientes 
                        WHERE apellidos LIKE :crit_like OR nombres LIKE :crit_like LIMIT 1";
                $stmt = $this->cnx->prepare($sql);
                $stmt->execute([':crit_like' => "%$criterio%"]);
            }

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en HistoriaModel::buscarPacientePorCriterio -> " . $e->getMessage());
            return false;
        }
    }

    // =========================================================================
    // 2. LISTAR EL HISTORIAL CLÍNICO COMPLETO DE UN PACIENTE (Para el DataTable)
    // =========================================================================
    public function listarHistorialPorPaciente($id_paciente)
    {
        try {
            // Relaciona historia_clinica con tu tabla nativa 'usuarios' mediante $this->cnx
            $sql = "SELECT h.id, h.fecha_registro, h.especialidad, h.tipo, h.resumen_clinico, h.ruta_archivo,
                           CONCAT(u.nombre, ' ', u.apellido) AS usuario_registro
                    FROM historia_clinica h
                    INNER JOIN usuarios u ON h.usuario_id = u.id
                    WHERE h.paciente_id = :id_paciente
                    ORDER BY h.fecha_registro DESC";
            
            $stmt = $this->cnx->prepare($sql);
            $stmt->execute([':id_paciente' => $id_paciente]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en HistoriaModel::listarHistorialPorPaciente -> " . $e->getMessage());
            return [];
        }
    }

    // =========================================================================
    // 3. ADMISIÓN - REGISTRAR ADJUNTO DIGITALIZADO
    // =========================================================================
    public function registrarAdjuntoDigitalizado($id_paciente, $id_usuario, $especialidad, $descripcion, $ruta_archivo)
    {
        try {
            $sql = "INSERT INTO historia_clinica (paciente_id, usuario_id, especialidad, tipo, resumen_clinico, ruta_archivo, fecha_registro)
                    VALUES (:paciente_id, :usuario_id, :especialidad, 'DIGITALIZADO', :descripcion, :ruta_archivo, NOW())";
            
            $stmt = $this->cnx->prepare($sql);
            return $stmt->execute([
                ':paciente_id'  => $id_paciente,
                ':usuario_id'   => $id_usuario,
                ':especialidad' => $especialidad,
                ':descripcion'  => $descripcion,
                ':ruta_archivo' => $ruta_archivo
            ]);
        } catch (PDOException $e) {
            error_log("Error en HistoriaModel::registrarAdjuntoDigitalizado -> " . $e->getMessage());
            return false;
        }
    }

    // =========================================================================
    // 4. MÉDICOS - REGISTRAR CONSULTA / EVOLUCIÓN INTERNA
    // =========================================================================
    public function registrarConsultaMedica($id_paciente, $id_usuario, $resumen, $sintomas, $examen, $diagnostico, $tratamiento)
    {
        try {
            /*$sql = "INSERT INTO historia_clinica (paciente_id, usuario_id, especialidad, tipo, resumen_clinico, sintomas, examen_fisico, diagnostico, treatment, fecha_registro)
                    VALUES (:paciente_id, :usuario_id, 'Medicina General', 'CONSULTA INTERNA', :resumen, :sintomas, :examen, :diagnostico, :tratamiento, NOW())";*/
            
            // Corrección semántica estricta apuntando a las columnas de tu BD: diagnostico, tratamiento
            $sql = "INSERT INTO historia_clinica (paciente_id, usuario_id, especialidad, tipo, resumen_clinico, sintomas, examen_fisico, diagnostico, tratamiento, fecha_registro)
                    VALUES (:paciente_id, :usuario_id, 'Medicina General', 'CONSULTA INTERNA', :resumen, :sintomas, :examen, :diagnostico, :tratamiento, NOW())";

            $stmt = $this->cnx->prepare($sql);
            return $stmt->execute([
                ':paciente_id' => $id_paciente,
                ':usuario_id'  => $id_usuario,
                ':resumen'     => $resumen,
                ':sintomas'    => $sintomas,
                ':examen'      => $examen,
                ':diagnostico' => $diagnostico,
                ':tratamiento' => $tratamiento
            ]);
        } catch (PDOException $e) {
            error_log("Error en HistoriaModel::registrarConsultaMedica -> " . $e->getMessage());
            return false;
        }
    }
}
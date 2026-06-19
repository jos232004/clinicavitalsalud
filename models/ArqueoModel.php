<?php
class ArqueoModel
{
    private $cnx;

    public function __construct()
    {
        // Usamos exactamente tu misma conexión estática
        $this->cnx = Conexion::conectar();
    }

    /**
     * 1. BUSCAR PACIENTE LOCAL
     * Busca en la tabla 'pacientes' si el DNI ya existe para jalar sus datos.
     */
    public function buscarPacienteLocal($dni)
    {
        $sql = "SELECT id, dni, nombres, apellidos, telefono,
                       IF(fecha_nac IS NOT NULL, TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()), NULL) AS edad 
                FROM pacientes 
                WHERE dni = :dni AND activo = 1 LIMIT 1";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute([':dni' => $dni]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 2. VERIFICAR EXISTENCIA SIMPLE (Mismo patrón de tu existeDni)
     */
    public function existeDniEnPacientes($dni)
    {
        $sql = "SELECT id FROM pacientes WHERE dni = :dni";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute([':dni' => $dni]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    /**
     * 3. REGISTRAR PACIENTE EN CALIENTE
     * Si no existe en la clínica, lo insertamos directo en 'pacientes' para futuras citas.
     */
    public function registrarPacienteDesdeCaja($dni, $nombres, $apellidos, $telefono)
    {
        $sql = "INSERT INTO pacientes (dni, nombres, apellidos, telefono, observaciones, activo)
                VALUES (:dni, :nombres, :apellidos, :telefono, 'Registrado automáticamente desde Arqueo', 1)";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':dni' => $dni,
            ':nombres' => $nombres,
            ':apellidos' => $apellidos,
            ':telefono' => !empty($telefono) ? $telefono : null
        ]);
    }

    /**
     * 4. REGISTRAR FILA DE ARQUEO
     * Guarda la fila unificada incluyendo la nueva columna es_consulta
     */
    public function registrarFilaArqueo($datos)
    {
        $sql = "INSERT INTO arqueos_caja (
                    fecha_arqueo, turno, nombre_paciente, dni_paciente, celular_paciente, 
                    edad_paciente, tipo_paciente, descripcion_servicio, medico_tratante, 
                    es_consulta, es_laboratorio, es_rayos_x, es_ekg, es_riesgo_quirurgico, es_ecografia, 
                    monto_cancelar, pago_medico_comision, egreso, metodo_pago, observaciones, usuario_id
                ) VALUES (
                    :fecha_arqueo, :turno, :nombre_paciente, :dni_paciente, :celular_paciente, 
                    :edad_paciente, :tipo_paciente, :descripcion_servicio, :medico_tratante, 
                    :es_consulta, :es_laboratorio, :es_rayos_x, :es_ekg, :es_riesgo_quirurgico, :es_ecografia, 
                    :monto_cancelar, :pago_medico_comision, :egreso, :metodo_pago, :observaciones, :usuario_id
                )";

        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':fecha_arqueo'          => $datos['fecha_arqueo'],
            ':turno'                 => $datos['turno'],
            ':nombre_paciente'       => !empty($datos['nombre_paciente']) ? $datos['nombre_paciente'] : null,
            ':dni_paciente'          => !empty($datos['dni_paciente']) ? $datos['dni_paciente'] : null,
            ':celular_paciente'      => !empty($datos['celular_paciente']) ? $datos['celular_paciente'] : null,
            ':edad_paciente'         => !empty($datos['edad_paciente']) ? $datos['edad_paciente'] : null,
            ':tipo_paciente'         => !empty($datos['tipo_paciente']) ? $datos['tipo_paciente'] : null,
            ':descripcion_servicio'  => $datos['descripcion_servicio'],
            ':medico_tratante'       => !empty($datos['medico_tratante']) ? $datos['medico_tratante'] : null,

            // Nueva columna insertada correctamente
            ':es_consulta'           => $datos['es_consulta'],

            ':es_laboratorio'        => $datos['es_laboratorio'],
            ':es_rayos_x'            => $datos['es_rayos_x'],
            ':es_ekg'                => $datos['es_ekg'],
            ':es_riesgo_quirurgico'  => $datos['es_riesgo_quirurgico'],
            ':es_ecografia'          => $datos['es_ecografia'],
            ':monto_cancelar'        => $datos['monto_cancelar'],
            ':pago_medico_comision'  => $datos['pago_medico_comision'],
            ':egreso'                => $datos['egreso'],
            ':metodo_pago'           => $datos['metodo_pago'],
            ':observaciones'         => !empty($datos['observaciones']) ? $datos['observaciones'] : null,
            ':usuario_id'            => $datos['usuario_id']
        ]);
    }

    /**
     * 5. OBTENER ARQUEO POR FECHA
     * Para renderizar las tablas o exportar a Excel.
     */
    public function obtenerArqueoPorFecha($fecha)
    {
        $sql = "SELECT *, 
                       -- Un helper para cuando pintes la celda de datos concatenados si deseas
                       COALESCE(metodo_pago, 'EFECTIVO') as metodo
                FROM arqueos_caja 
                WHERE fecha_arqueo = :fecha 
                ORDER BY turno ASC, id ASC";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

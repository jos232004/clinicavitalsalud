<?php
class PacienteModel
{
    private $cnx;

    public function __construct()
    {
        // Llamamos directamente al método estático de tu archivo de conexión
        $this->cnx = Conexion::conectar();
    }

    public function listarPacientes()
    {
        // Añadimos 'fecha_nac' y calculamos la edad en tiempo real con TIMESTAMPDIFF para mostrarla en tu tabla
        $sql = "SELECT id, dni, nombres, apellidos, fecha_nac, 
                       TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE()) AS edad, 
                       telefono 
                FROM pacientes";
        $stmt = $this->cnx->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarPaciente($dni, $nombres, $apellidos, $fecha_nac, $telefono)
    {
        // Añadimos el campo 'fecha_nac' al INSERT
        $sql = "INSERT INTO pacientes (dni, nombres, apellidos, fecha_nac, telefono)
                VALUES (:dni, :nombres, :apellidos, :fecha_nac, :telefono)";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':dni' => $dni,
            ':nombres' => $nombres,
            ':apellidos' => $apellidos,
            ':fecha_nac' => !empty($fecha_nac) ? $fecha_nac : null, // Si viene vacío, guarda NULL de forma segura
            ':telefono' => $telefono
        ]);
    }

    public function editarPaciente($id, $dni, $nombres, $apellidos, $fecha_nac, $telefono)
    {
        // Añadimos el campo 'fecha_nac' al UPDATE
        $sql = "UPDATE pacientes 
                SET dni=:dni, nombres=:nombres, apellidos=:apellidos, fecha_nac=:fecha_nac, telefono=:telefono 
                WHERE id=:id";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':dni' => $dni,
            ':nombres' => $nombres,
            ':apellidos' => $apellidos,
            ':fecha_nac' => !empty($fecha_nac) ? $fecha_nac : null, // Control de nulos
            ':telefono' => $telefono,
            ':id' => $id
        ]);
    }

    public function eliminarPaciente($id)
    {
        $sql = "DELETE FROM pacientes WHERE id=:id";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /*FUNCIONES DE SEGURIDAD*/
    public function existeDni($dni, $id = null)
    {
        $sql = "SELECT id FROM pacientes WHERE dni = :dni";
        if ($id) {
            $sql .= " AND id != :id"; // excluir el paciente actual en edición
        }
        $stmt = $this->cnx->prepare($sql);
        $params = [':dni' => $dni];
        if ($id) $params[':id'] = $id;
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
}

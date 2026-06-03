<?php
class MedicoModel
{
    private $cnx;

    public function __construct()
    {
        // Usamos tu clase de conexión centralizada
        $this->cnx = Conexion::conectar();
    }

    // MODIFICADO: Ahora trae TODOS los médicos y su estado actual
    public function listarMedicos()
    {
        $sql = "SELECT m.id, m.nombres, m.apellidos, m.dni, m.telefono, m.email, m.cmp, m.activo, e.nombre AS especialidad
                FROM medicos m
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                ORDER BY m.apellidos ASC, m.nombres ASC";
        $stmt = $this->cnx->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarMedico($especialidad_id, $nombres, $apellidos, $dni, $telefono, $email, $cmp)
    {
        $sql = "INSERT INTO medicos (especialidad_id, nombres, apellidos, dni, telefono, email, cmp)
                VALUES (:especialidad_id, :nombres, :apellidos, :dni, :telefono, :email, :cmp)";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':especialidad_id' => $especialidad_id,
            ':nombres' => $nombres,
            ':apellidos' => $apellidos,
            ':dni' => $dni,
            ':telefono' => $telefono,
            ':email' => $email,
            ':cmp' => $cmp
        ]);
    }

    public function editarMedico($id, $especialidad_id, $nombres, $apellidos, $dni, $telefono, $email, $cmp)
    {
        $sql = "UPDATE medicos 
                SET especialidad_id=:especialidad_id, nombres=:nombres, apellidos=:apellidos, dni=:dni, 
                    telefono=:telefono, email=:email, cmp=:cmp 
                WHERE id=:id";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':especialidad_id' => $especialidad_id,
            ':nombres' => $nombres,
            ':apellidos' => $apellidos,
            ':dni' => $dni,
            ':telefono' => $telefono,
            ':email' => $email,
            ':cmp' => $cmp,
            ':id' => $id
        ]);
    }

    // MANTIENE EL SOFT DELETE: Por si usas el botón directo de eliminar
    public function eliminarMedico($id)
    {
        $sql = "UPDATE medicos SET activo=0 WHERE id=:id";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // NUEVO MÉTODO: Para cambiar el estado (Activar/Desactivar) de forma dinámica
    public function actualizarEstadoMedico($id, $estado)
    {
        // $estado recibirá 1 (Activo) o 0 (Inactivo) desde el controlador
        $sql = "UPDATE medicos SET activo = :estado WHERE id = :id";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':estado' => $estado,
            ':id' => $id
        ]);
    }

    // SE MANTIENE IGUAL: Solo filtra activos para la asignación de citas
    public function listarMedicosPorEspecialidad($especialidad_id)
    {
        $sql = "SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre_completo 
                FROM medicos 
                WHERE especialidad_id = :especialidad_id AND activo = 1
                ORDER BY apellidos ASC, nombres ASC";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute([':especialidad_id' => $especialidad_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* FUNCIONES DE SEGURIDAD */
    
    public function existeDni($dni, $id = null)
    {
        $sql = "SELECT id FROM medicos WHERE dni = :dni";
        if ($id) {
            $sql .= " AND id != :id";
        }
        $stmt = $this->cnx->prepare($sql);
        $params = [':dni' => $dni];
        if ($id) $params[':id'] = $id;
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
}

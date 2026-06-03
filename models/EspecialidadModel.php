<?php
class EspecialidadModel
{
    private $cnx;

    public function __construct()
    {
        // Llamamos directamente al método estático de tu archivo de conexión
        $this->cnx = Conexion::conectar();
    }

    public function listarEspecialidades()
    {
        $sql = "SELECT id, nombre, descripcion FROM especialidades";
        $stmt = $this->cnx->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarEspecialidad($nombre, $descripcion)
    {
        $sql = "INSERT INTO especialidades (nombre, descripcion)
                VALUES (:nombre, :descripcion)";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion
        ]);
    }

    public function editarEspecialidad($id, $nombre, $descripcion)
    {
        $sql = "UPDATE especialidades 
                SET nombre=:nombre, descripcion=:descripcion 
                WHERE id=:id";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':id' => $id
        ]);
    }

    public function eliminarEspecialidad($id)
    {
        $sql = "DELETE FROM especialidades WHERE id=:id";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function existeNombre($nombre, $id = null)
    {
        $sql = "SELECT id FROM especialidades WHERE nombre = :nombre";
        if ($id) {
            $sql .= " AND id != :id";
        }
        $stmt = $this->cnx->prepare($sql);
        $params = [':nombre' => $nombre];
        if ($id) $params[':id'] = $id;
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
}

<?php
//include_once("../config/conexion.php"); // Temporalmente comentado para debug

//USUARIO MODELO: Aqui defininimos nuestros metodos para la comunicacion con la base de datos

class UsuarioModel
{
    private $cnx;

    public function __construct()
    {
        // Llamamos directamente al método estático de tu archivo de conexión
        $this->cnx = Conexion::conectar();
    }

    // Función para verificar las credenciales del usuario
    public function verificarUsuario($email, $clave)
    {
        try {
            $sql = "SELECT * FROM usuarios
                    WHERE email=:email AND activo=1";
            // global $cnx; // Removido, ahora usamos $this->cnx
            $parametros = array(':email' => $email);
            $pre = $this->cnx->prepare($sql);
            $pre->execute($parametros);
            $resultado = $pre->fetch(PDO::FETCH_ASSOC);

            if ($resultado === false) {
                error_log("Usuario no encontrado: $email");
                return false;
            }

            // Verificar la contraseña con bcrypt
            if (password_verify($clave, $resultado['password'])) {
                error_log("Login exitoso para: $email");
                return $resultado;
            } else {
                error_log("Contraseña incorrecta para: $email");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error en verificarUsuario: " . $e->getMessage());
            return false;
        }
    }

    //Listar usuarios
    public function listarUsuarios()
    {
        $sql = "SELECT id, nombre, apellido, email, rol, activo FROM usuarios";
        $stmt = $this->cnx->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Registramos usuario
    public function registrarUsuario($nombre, $apellido, $email, $password, $rol)
    {
        $sql = "INSERT INTO usuarios (nombre, apellido, email, password, rol)
            VALUES (:nombre, :apellido, :email, :password, :rol)";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':password' => $password,
            ':rol' => $rol
        ]);
    }

    //Editamos usuarios
    public function editarUsuario($id, $nombre, $apellido, $email, $rol, $activo)
    {
        $sql = "UPDATE usuarios 
            SET nombre=:nombre, apellido=:apellido, email=:email, rol=:rol, activo=:activo 
            WHERE id=:id";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':rol' => $rol,
            ':activo' => $activo,
            ':id' => $id
        ]);
    }

    //Desactivar usuario.
    public function eliminarUsuario($id)
    {
        $sql = "UPDATE usuarios SET activo=0 WHERE id=:id";
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /*  FUNCIONES DE SEGURIDAD  */

    //Verificamos si el email ya existe
    public function existeEmail($email, $id = null)
    {
        $sql = "SELECT id FROM usuarios WHERE email = :email AND activo=1";
        if ($id) {
            $sql .= " AND id != :id"; // excluir el usuario actual en edición
        }
        $stmt = $this->cnx->prepare($sql);
        $params = [':email' => $email];
        if ($id) $params[':id'] = $id;
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
}

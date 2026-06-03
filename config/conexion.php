<?php
// config/conexion.php

class Conexion
{
    public static function conectar()
    {
        $manejador = "mysql";
        $servidor = "localhost";
        $usuario = "root";
        $pass = "";//Configurar la base de datos con la contraseña de su servidor local
        $base = "vital_salud";
        $cadena = "$manejador:host=$servidor;dbname=$base";

        try {
            // Usamos utf8mb4 para evitar problemas con acentos o eñes
            $cnx = new PDO($cadena, $usuario, $pass, array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"
            ));
            return $cnx;
        } catch (Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}

<?php

class Conexion{
    private $dbh;
    private static $instancia;
    private $host = 'bqk08kny3j3dwd1zyv1n-mysql.services.clever-cloud.com';
    private $usuario = 'udey1lqz5dwvtxrr';
    private $password = 'LlQOMJyIYCi862csU8Le';
    private $dbName = 'bqk08kny3j3dwd1zyv1n';

    // Funcion instancia
    public static function getInstancia(){
        if(!self::$instancia){
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    // Constructor
    private function __construct(){
        try{
            $this->dbh = new PDO("mysql:host=" . $this->host . ";port=3306;dbname=" . $this->dbName, $this->usuario, $this->password);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            //Mostrar mensaje de error
            print("Error al cargar la base de datos");
            exit();
            die();
        }
    }

    // Funcion getDbh()
    public function getDbh(){
        return $this->dbh;
    }
}
?>
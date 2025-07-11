<?php
include_once '../conexion.php';

class AlumnoDb{
    
    // Funcion para agregar 
    public function addAlumno($username, $email, $pass){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'INSERT INTO Alumnos(username, email, pass) VALUES (? ,?, ?)';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $pass);
            $stmt->execute();
            $dbh = null;
        } catch (PDOException $e){
            echo "Error al ingresar los datos";
        }
    }

    //Función para mostrar datos
    public function showAlumno(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'SELECT username, email FROM Alumnos';
            $stmt = $dbh->prepare($consulta);
            $stmt->execute();
            $datos = $stmt->fetch();
            $dbh = null;
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    // Función para actualizar la contraseña
    public function updatePasAlumno($email, $pass){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'UPDATE pass SET Alumno WHERE = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $email);
            $stmt->execute();
        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}
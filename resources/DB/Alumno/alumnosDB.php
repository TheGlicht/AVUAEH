<?php
include_once __DIR__ . '/../conexion.php';

class AlumnoDb {
    
    // Funcion para agregar a un Alumno
    public function addAlumno($username, $email, $pass) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();

        try {
            // Verificar si el alumno ya está registrado
            $verifica = 'SELECT COUNT(*) FROM Alumno WHERE email = ?';
            $stmt = $dbh->prepare($verifica);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $existe = $stmt->fetchColumn();

            if ($existe > 0) {
                throw new Exception("Este correo ya está registrado. Intenta con otro.");
            }

            // Insertar nuevo alumno
            $consulta = 'INSERT INTO Alumno(username, email, pass) VALUES (?, ?, ?)';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $pass);
            $stmt->execute();

            $dbh = null;
        } catch (PDOException $e) {
            throw new Exception("Error al ingresar los datos: " . $e->getMessage());
        }
    }

    //Función para mostrar datos de un solo alumno
    public function showAlumno($username){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT username, email FROM Alumno WHERE username = :username';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC); // Solo un resultado
            return $datos;
        } catch(PDOException $e){
            echo $e->getMessage();
            return null;
        }
    }


    // Funcion para actualizar contraseña
    public function updatePasAlumno($email, $pass){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'UPDATE Alumno SET pass = ? WHERE email = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $pass);
            $stmt->bindParam(2, $email);
            $stmt->execute();
    
            if ($stmt->rowCount() === 0) {
                throw new Exception("No se encontró ningún alumno con ese correo.");
            }
    
            $dbh = null;
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar contraseña.");
        }
    }

    // Funcion para obtener la contraseña por email
    public function getPasswordByEmail($email) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT pass FROM Alumno WHERE email = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $dbh = null;
            return $resultado['pass'] ?? null;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener contraseña.");
        }
    }    

    // Funcion para obtener el username por el email
    public function getUsernameByEmail($email) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT username FROM Alumno WHERE email = :email';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            return $datos['username'] ?? null;
        } catch (PDOException $e) {
            return null;
        }
    }
 
    
}
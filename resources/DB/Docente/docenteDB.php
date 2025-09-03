<?php
include_once __DIR__ . '/../conexion.php';

class DocenteDb {
    // Funcion para agregar a un Docente
    public function addDocente($username, $email, $pass){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            // Verifica si el docente ya esta registrado
            $verifica = 'SELECT COUNT(*) FROM Docentes WHERE email = ?';
            $stmt = $dbh->prepare($verifica);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $existe = $stmt->fetchColumn();

            if($existe > 0){
                throw new Exception("Este correo ya está registrado. Intente con otro");
            }

            // Verifica si no es un correo de alumno
            $sql = 'SELECT COUNT(*) FROM Alumno WHERE email = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $existe2 = $stmt->fetchColumn();

           if($existe2 >0){
            throw new Exception("Este correo ya est+a registrado como alumno.");
           }

            // Insertar nuevo docente
            $consulta = 'INSERT INTO Docentes(username, email, pass) VALUES (?, ?, ?)';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $pass);
            $stmt->execute();

            $dbh = null;
        } catch(PDOException $e){
            throw new Exception("Error al ingresar los datos:". $e->getMessage());
        }
    }

    // Funcion para mostrar los datos de un solo docente
    public function showDocente($username){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'SELECT username, email FROM Docentes WHERE username = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            return $datos;
        } catch (PDOException $e){
            echo $e->getMessage();
            return null;
        }
    }

    // Funcion para actualizar contraseña
    public function updatePasDocente($email, $pass){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();  // corregido $dnh a $dbh
        try {
            $consulta = 'UPDATE Docentes SET pass = ? WHERE email = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $pass);
            $stmt->bindParam(2, $email);
            $stmt->execute();
            $dbh = null;
            return true; // o algún indicador de éxito
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar contraseña: " . $e->getMessage());
        }
    }
    
    // Funcion para obtener la contraseña por email
    public function getPasswordByEmail($email){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'SELECT pass FROM Docentes WHERE email = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $dbh = null;
            return $resultado['pass'] ?? null;
        }catch(PDOException $e){
            throw new Exception("Error al obtener contraseña");
        }
    }

    // Funcion para obtener el username por el email
    public function getUsernameByEmail($email){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'SELECT username FROM Docentes WHERE email = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            return $datos['username'] ?? null;
            $dbh= null;
        }catch(PDOException $e){
            return null;
        }
    }
}
?>
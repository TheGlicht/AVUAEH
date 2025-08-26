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
        $dnh = $conexion->getDbh();
        try{;
            $consulta = 'SELECT pass FROM Docentes WHERE email = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $resultado = $stmt->fetch(POD::FETCH_ASSOC);
            return $resultado['pass'] ?? null;
            $dbh = null;
        } catch (PDOException $e){
            throw new Exception("Error a actualizar contraseña");
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
<?php
include_once __DIR__ . "/../conexion.php";

class LaboratorioDb{
    // Funcion para agregar un Encargado
    public function addLab($username, $email, $pass){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            // ALUMNO
            // Verificar si el email ya está registrado
            $verifica = 'SELECT COUNT(*) FROM Alumno WHERE email = ?';
            $stmt = $dbh->prepare($verifica);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $existe = $stmt->fetchColumn();

            // Verificar si el username ya esta registrado
            $verificaUser = 'SELECT COUNT(*) FROM Alumno WHERE username = ?';
            $stmt = $dbh->prepare($verificaUser);
            $stmt->execute([$username]);
            $existe2 = $stmt->fetchColumn();

            // CONSULTAS
            if ($existe > 0) {
                throw new Exception("Este correo ya está registrado como Alumno");
            }
            if ($existe2 > 0){
                throw new Exception("Este nombre de usuario ya esta registrad Alumno");
            }

            // DOCENTES
            // Verificar si el email ya esta registrado
            $verifica2 = 'SELECT COUNT(*) FROM Docentes WHERE email = ?';
            $stmt = $dbh->prepare($verifica2);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $existe3 = $stmt->fetchColumn();

            // Verficiar si el username ya esta registrado
            $verificaUser2 = 'SELECT COUNT(*) FROM Docentes WHERE username = ?';
            $stmt = $dbh->prepare($verificaUser2);
            $stmt->execute([$username]);
            $existe4 = $stmt->fetchColumn();

            // CONSULTAS
            if ($existe3 > 0) {
                throw new Exception("Este correo ya está registrado como Docente");
            }
            if ($existe4 > 0){
                throw new Exception("Este nombre de usuario ya esta registrado como Docente");
            }

            //LABORATORIO
            // Verificar si el email ya esta registrado
            $verifica3 = 'SELECT COUNT(*) FROM Laboratorio WHERE email = ?';
            $stmt = $dbh->prepare($verifica3);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $existe5 = $stmt->fetchColumn();

            // Verificar si el username ya esta registrado
            $verificaUser3 = 'SELECT COUNT(*) FROM Docentes WHERE username = ?';
            $stmt = $dbh->prepare($verificaUser3);
            $stmt->execute([$username]);
            $existe6 = $stmt->fetchColumn();

            // CONSULTAS
            if ($existe5 > 0) {
                throw new Exception("Este correo ya está registrado como Encargado de Laboratorio");
            }
            if ($existe6 > 0){
                throw new Exception("Este nombre de usuario ya esta registrado como Encargado de Laboratorio");
            }

            //Insertar encargado de laboratorio
           $consulta = 'INSERT INTO Laboratorio(username, email, pass) VALUES(?, ?, ?)';
           $stmt = $dbh->prepare($consulta);
           $stmt->bindParam(1, $username);
           $stmt->bindParam(2, $email);
           $stmt->bindParam(3, $pass);
           $stmt->execute();

           $dbh = null;
        } catch(PDOExceptionn $e){
            throw  new Exception("Error al ingresar los datos:" . $e->getMessage());
        }
    }

    // Funcion para mostrar los datos de un solo encargado
    public function showLaboratorio($username){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'SELECT username, email FROM Laboratorio WHERE username = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            return $datos;
            $dbh = null;
        } catch (PODException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    // Funcion para actualizar contraseña
    public function updatePasLab($email, $pass){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
          $consulta = 'UPDATE Laboratorio SET pass = ? WHERE email = ?';
          $stmt = $dbh->prepare($consulta);
          $stmt->bindParam(1, $pass);
          $stmt->bindParam(2, $email);
          $stmt->execute();

          if($stmt->rowCount() === 0){
            throw new Exception("No se encontró ningun Encargado con este correo");
          }
          $dbbh = null;
        } catch (PDOException $e){
            throw new Exception("Error a actualizar contraseña");
        }
    }

    // Funcion para obtener la contraseña por email
    public function getPasswordByEmail($email) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT pass FROM Laboratorio WHERE email = ?';
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
    public function getUsernameByEmail($email){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'SELECT username FROM Laboratorio WHERE email = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            return $datos['username'] ?? null;
            $dbh = null;
        } catch(PDOException $e){
            return null;
        }
    }
} 
?>
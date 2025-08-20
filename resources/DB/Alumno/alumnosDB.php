<?php
include_once __DIR__ . '/../conexion.php';

// Clase relacionada con el email, nombre de usuario y contraseña
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

// Clase relacionada con el perfil general del usuario.
class DataAlumnoDb{
    // Funcion para agregar los informacion del alumno
    public function addADatos($nombre, $semestre, $grupo, $username){
        $conexion = Conexion::getInstancia();
        $dbh= $conexion->getDbh();

        try{
            // Primero obtener el id_alumno
            $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $id_alumno = $stmt->fetchColumn();
            if (!$id_alumno) return false;

            // Insertar datos nuevos
            $consulta = 'INSERT INTO DatosA(nombreCompleto, semestre, grupo, id_alumno) VALUES(?, ?, ?, ?)';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $semestre);
            $stmt->bindParam(3, $grupo);
            $stmt->bindParam(4, $id_alumno);
            return $stmt->execute();
            $dbh = null;
        } catch (PODException $e){
            return false;
        }
    }

   // Funcion para obtener la información del alumno
   public function getADatos($username) {
    try {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();

        $sql = 'SELECT d.id_DatosA, d.nombreCompleto, d.semestre, d.grupo, a.username
                FROM DatosA d 
                JOIN Alumno a ON a.id_alumno = d.id_alumno
                WHERE a.username = ?';

        $stmt = $dbh->prepare($sql);
        $stmt->execute([$username]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error en getADatos: " . $e->getMessage());
    }
}

    // Funcion para actualizar los datos
    public function updateADatos($nombre, $semestre, $grupo, $username){
    $conexion = Conexion::getInstancia();
    $dbh = $conexion->getDbh();

    try {
        // Obtener id_alumno
        $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $id_alumno = $stmt->fetchColumn();
        if (!$id_alumno) return false;

        // Actualizar DatosA
        $consulta = 'UPDATE DatosA SET nombreCompleto = ?, semestre = ?, grupo = ? WHERE id_alumno = ?';
        $stmt = $dbh->prepare($consulta);
        $stmt->execute([$nombre, $semestre, $grupo, $id_alumno]);

        // Actualizar Alumno
        $consulta2 = 'UPDATE Alumno SET username = ? WHERE id_alumno = ?';
        $stmt = $dbh->prepare($consulta2);
        $stmt->execute([$username, $id_alumno]);

        return true;  // 👈 AHORA sí devuelve true
    } catch (PDOException $e) {
        return false;
    }
}

}
?>
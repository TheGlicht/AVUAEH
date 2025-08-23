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

class DataAlumnoDb
{
    // Funcion para obtener los del perfil de los alumnos
    public function getADatos(string $username)
    {
        try {
            $conexion = Conexion::getInstancia();
            $dbh = $conexion->getDbh();

            $sql = 'SELECT 
                        a.id_alumno,
                        a.username,
                        d.id_DatosA,
                        d.nombreCompleto,
                        d.semestre,
                        d.grupo
                    FROM Alumno a
                    LEFT JOIN DatosA d ON d.id_alumno = a.id_alumno
                    WHERE a.username = ?';

            $stmt = $dbh->prepare($sql);
            $stmt->execute([$username]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            return [
                'nombreCompleto' => $row['nombreCompleto'] ?? '',
                'semestre'       => $row['semestre'] ?? '',
                'grupo'          => $row['grupo'] ?? '',
                'username'       => $row['username'],
            ];
        } catch (PDOException $e) {
            throw new Exception("Error en getADatos: " . $e->getMessage());
        }
    }


    // Funcion para agregar los datos del perfil del usuario
    public function addADatos(string $nombre, int $semestre, int $grupo, string $currentUsername)
    {
        try {
            $conexion = Conexion::getInstancia();
            $dbh = $conexion->getDbh();

            // id_alumno a partir del username actual
            $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$currentUsername]);
            $id_alumno = $stmt->fetchColumn();
            if (!$id_alumno) {
                return false;
            }

            // Verificar si ya existe registro en DatosA
            $check = $dbh->prepare('SELECT COUNT(1) FROM DatosA WHERE id_alumno = ?');
            $check->execute([$id_alumno]);
            $exists = (int)$check->fetchColumn() > 0;

            if ($exists) {
                // Ya existe, actualiza
                $upd = $dbh->prepare('UPDATE DatosA SET nombreCompleto=?, semestre=?, grupo=? WHERE id_alumno=?');
                return $upd->execute([$nombre, $semestre, $grupo, $id_alumno]);
            }

            // No existe, inserta
            $ins = $dbh->prepare('INSERT INTO DatosA (nombreCompleto, semestre, grupo, id_alumno) VALUES (?, ?, ?, ?)');
            return $ins->execute([$nombre, $semestre, $grupo, $id_alumno]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Funcion para actualizar los datos del perfil
    public function upsertADatos(string $nombre, int $semestre, int $grupo, string $newUsername, string $currentUsername)
    {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();

        try {
            $dbh->beginTransaction();

            // Obtener id_alumno del dueño (username actual de sesión)
            $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$currentUsername]);
            $id_alumno = $stmt->fetchColumn();

            if (!$id_alumno) {
                $dbh->rollBack();
                return "Alumno no encontrado";
            }

            // Si desea cambiar el username, validar que no esté en uso por otro
            if ($newUsername !== $currentUsername) {
                $ver = $dbh->prepare('SELECT COUNT(1) FROM Alumno WHERE username = ? AND id_alumno <> ?');
                $ver->execute([$newUsername, $id_alumno]);
                if ((int)$ver->fetchColumn() > 0) {
                    $dbh->rollBack();
                    return "El nombre de usuario ya está en uso";
                }
            }

            // Upsert en DatosA
            $check = $dbh->prepare('SELECT COUNT(1) FROM DatosA WHERE id_alumno = ?');
            $check->execute([$id_alumno]);
            $exists = (int)$check->fetchColumn() > 0;

            if ($exists) {
                $upd = $dbh->prepare('UPDATE DatosA SET nombreCompleto = ?, semestre = ?, grupo = ? WHERE id_alumno = ?');
                $upd->execute([$nombre, $semestre, $grupo, $id_alumno]);
            } else {
                $ins = $dbh->prepare('INSERT INTO DatosA (nombreCompleto, semestre, grupo, id_alumno) VALUES (?, ?, ?, ?)');
                $ins->execute([$nombre, $semestre, $grupo, $id_alumno]);
            }

            // Actualizar username si cambió
            if ($newUsername !== $currentUsername) {
                $updUser = $dbh->prepare('UPDATE Alumno SET username = ? WHERE id_alumno = ?');
                $updUser->execute([$newUsername, $id_alumno]);
            }

            $dbh->commit();
            return true;
        } catch (PDOException $e) {
            if ($dbh->inTransaction()) {
                $dbh->rollBack();
            }
            return false;
        }
    }
}
?>
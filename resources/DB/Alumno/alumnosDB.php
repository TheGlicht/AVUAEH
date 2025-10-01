<?php
include_once __DIR__ . '/../conexion.php';

// Clase relacionada con el email, nombre de usuario y contraseña
class AlumnoDb {
    // Funcion para agregar a un Alumno
    public function addAlumno($username, $email, $pass) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();

        try {
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
    // Funcion para obtener los datos del perfil de los alumnos
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
                    d.grupo,
                    d.numero
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
                'telefono'       => $row['numero'] ?? '',
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
    public function upsertADatos(string $nombre, int $semestre, int $grupo, string $telefono , string $newUsername, string $currentUsername)
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
                $upd = $dbh->prepare('UPDATE DatosA 
                                      SET nombreCompleto = ?, semestre = ?, grupo = ?, numero = ? 
                                      WHERE id_alumno = ?');
                $upd->execute([$nombre, $semestre, $grupo, $telefono, $id_alumno]);
            } else {
                $ins = $dbh->prepare('INSERT INTO DatosA (nombreCompleto, semestre, grupo, numero, id_alumno) 
                                      VALUES (?, ?, ?, ?, ?)');
                $ins->execute([$nombre, $semestre, $grupo, $telefono, $id_alumno]);
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

// Sugerencias de compañeros
public function getSugerencias(string $username): array {
    $conexion = Conexion::getInstancia();
    $dbh = $conexion->getDbh();
    
    // Obtener datos del alumno actual
    $yo = null;
    try {
        $sql = 'SELECT a.id_alumno, d.semestre, d.grupo
                FROM Alumno a
                LEFT JOIN DatosA d ON d.id_alumno = a.id_alumno
                WHERE a.username = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$username]);
        $yo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$yo) {
            error_log("No se encontró alumno con username: $username");
            return [];
        }
    } catch (PDOException $e) {
        error_log("Error al obtener datos del alumno $username: " . $e->getMessage());
        return [];
    }

    $id_alumno = $yo['id_alumno'];
    $semestre  = $yo['semestre'] ?? null;
    $grupo     = $yo['grupo'] ?? null;

    // 🔹 Obtener lista de contactos ya guardados (por email o id_alumno)
    $excluir = [];
    try {
        $sqlC = 'SELECT c.email, c.id_contacto, a.id_alumno AS id_owner
        FROM ContactosA c
        JOIN Alumno a ON a.id_alumno = c.id_alumno
        WHERE a.username = ?';
        $stmtC = $dbh->prepare($sqlC);
        $stmtC->execute([$username]);
        $contactos = $stmtC->fetchAll(PDO::FETCH_ASSOC);

        foreach ($contactos as $c) {
            if (!empty($c['email'])) {
                $excluir['email'][$c['email']] = true;
            }
            if (!empty($c['id_alumno'])) {
                $excluir['id'][$c['id_alumno']] = true;
            }
        }
    } catch (PDOException $e) {
        error_log("Error obteniendo contactos guardados: " . $e->getMessage());
    }

    $sugerencias = [];

    // 🔹 Buscar por grupo/semestre
    if (!empty($semestre) && !empty($grupo)) {
        try {
            $sql1 = 'SELECT a.id_alumno, a.username, a.email, d.nombreCompleto, d.semestre, d.grupo, d.numero AS telefono
                     FROM Alumno a
                     JOIN DatosA d ON d.id_alumno = a.id_alumno
                     WHERE d.semestre = ? AND d.grupo = ? AND a.id_alumno <> ?';
            $stmt1 = $dbh->prepare($sql1);
            $stmt1->execute([$semestre, $grupo, $id_alumno]);
            $grupoCoincide = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($grupoCoincide as $row) {
                if (
                    (isset($excluir['id'][$row['id_alumno']])) ||
                    (isset($excluir['email'][$row['email']]))
                ) {
                    continue; // ⚠️ ya existe en ContactosA, no sugerir
                }
                $sugerencias[$row['username']] = $row;
            }
        } catch (PDOException $e) {
            error_log("Error en búsqueda por grupo para $username: " . $e->getMessage());
        }
    }

    // 🔹 Buscar por materias en común
    try {
        $sql2 = 'SELECT DISTINCT a.id_alumno, a.username, a.email, d.nombreCompleto, 
                        d.semestre, d.grupo, d.numero AS telefono
                 FROM Alumno a
                 JOIN DatosA d ON d.id_alumno = a.id_alumno
                 JOIN AluMateria am ON am.id_alumno = a.id_alumno
                 WHERE am.id_materias IN (
                     SELECT id_materias FROM AluMateria WHERE id_alumno = ?
                 )
                 AND a.id_alumno <> ? 
                 AND d.nombreCompleto IS NOT NULL 
                 AND d.nombreCompleto != ""';
        $stmt2 = $dbh->prepare($sql2);
        $stmt2->execute([$id_alumno, $id_alumno]);
        $materiaCoincide = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($materiaCoincide as $row) {
            if (
                (isset($excluir['id'][$row['id_alumno']])) ||
                (isset($excluir['email'][$row['email']]))
            ) {
                continue; // ⚠️ ya existe en ContactosA, no sugerir
            }
            $sugerencias[$row['username']] = $row;
        }
    } catch (PDOException $e) {
        error_log("Error en búsqueda por materias para $username: " . $e->getMessage());
    }

    $resultado = array_values($sugerencias);
    error_log("Sugerencias generadas para $username: " . count($resultado) . " resultados");

    return $resultado;
}




}
?>
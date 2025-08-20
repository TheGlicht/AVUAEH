<?php
include_once __DIR__ . '/../conexion.php';

class ContactoDb {
    // Crear contacto
    public function addContacto($nombreCompleto, $telefono, $email, $username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            // Obtener id_alumno por username
            $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $id_alumno = $stmt->fetchColumn();

            if (!$id_alumno) return false;

            // Insertar contacto
            $consulta = 'INSERT INTO ContactosA (nombreCompleto, telefono, email, id_alumno)
                         VALUES (?, ?, ?, ?)';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $nombreCompleto);
            $stmt->bindParam(2, $telefono);
            $stmt->bindParam(3, $email);
            $stmt->bindParam(4, $id_alumno);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en addContacto: " . $e->getMessage());
            return false;
        }
    }

    // Listar contactos del alumno
    public function getContactosByAlumno($username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $sql = 'SELECT c.id_contacto, c.nombreCompleto, c.telefono, c.email
                    FROM ContactosA c
                    JOIN Alumno a ON a.id_alumno = c.id_alumno
                    WHERE a.username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getContactosByAlumno: " . $e->getMessage());
            return [];
        }
    }

    // Actualizar contacto
    public function updateContacto($id_contacto, $nombreCompleto, $telefono, $email, $username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            // Verificar propietario
            $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $id_alumno = $stmt->fetchColumn();

            if (!$id_alumno) return false;

            $consulta = 'UPDATE ContactosA
                         SET nombreCompleto = ?, telefono = ?, email = ?
                         WHERE id_contacto = ? AND id_alumno = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $nombreCompleto);
            $stmt->bindParam(2, $telefono);
            $stmt->bindParam(3, $email);
            $stmt->bindParam(4, $id_contacto);
            $stmt->bindParam(5, $id_alumno);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en updateContacto: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar contacto
    public function deleteContacto($id_contacto, $username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            // Verificar propietario
            $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $id_alumno = $stmt->fetchColumn();

            if (!$id_alumno) return false;

            $consulta = 'DELETE FROM ContactosA WHERE id_contacto = ? AND id_alumno = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $id_contacto);
            $stmt->bindParam(2, $id_alumno);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en deleteContacto: " . $e->getMessage());
            return false;
        }
    }
}

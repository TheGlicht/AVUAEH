<?php
include_once __DIR__ . '/../conexion.php';

class EventoDb {
    // Agregar evento
    public function addEvento($titulo, $descripcion, $fecha, $username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $id_alumno = $stmt->fetchColumn();
    
            if (!$id_alumno) {
                throw new Exception("Este usuario no está registrado.");
            }
    
            // Insertar evento
            $consulta = 'INSERT INTO EventosA (tituloEvento, descripcion, fechaEvento, id_alumno) VALUES (?, ?, ?, ?)';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $titulo);
            $stmt->bindParam(2, $descripcion);
            $stmt->bindParam(3, $fecha);
            $stmt->bindParam(4, $id_alumno);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    

    // Obtener eventos por alumno
    public function getEventosByEmail($username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        $sql = 'SELECT e.id_evento, e.tituloEvento, e.descripcion, e.fechaEvento 
                FROM EventosA e
                JOIN Alumno a ON a.id_alumno = e.id_alumno
                WHERE a.username = ?'; // Cambia email por username
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Editar evento
    public function updateEvento($id_evento, $titulo, $descripcion, $fecha, $username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        
        // Primero obtener el id_alumno
        $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $id_alumno = $stmt->fetchColumn();
    
        if (!$id_alumno) return false;
    
        // Actualizar el evento
        $sql = 'UPDATE EventosA SET 
                tituloEvento = ?,
                descripcion = ?,
                fechaEvento = ?
                WHERE id_evento = ? AND id_alumno = ?';
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $titulo);
        $stmt->bindParam(2, $descripcion);
        $stmt->bindParam(3, $fecha);
        $stmt->bindParam(4, $id_evento);
        $stmt->bindParam(5, $id_alumno);
        
        return $stmt->execute();
    }
    

    // Eliminar evento
    public function deleteEvento($id_evento, $username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        
        // Primero, obtenemos el id_alumno del usuario
        $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $id_alumno = $stmt->fetchColumn();

        if (!$id_alumno) {
            return false; // Usuario no encontrado
        }

        // Ahora eliminamos el evento
        $sql = 'DELETE FROM EventosA WHERE id_evento = ? AND id_alumno = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $id_evento);
        $stmt->bindParam(2, $id_alumno);
        return $stmt->execute();
    }

}
?>
<?php
include_once __DIR__ . '/../conexion.php';

// Se encarga solo de buscar las materias relacionadas con el docente
class MateriasDocente{
    public function getMateriasDoc($username){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $sql = 'SELECT id_docente FROM Docentes WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $id_docente = $stmt->fetchColumn();
            
            if(!$id_docente){
                return false;
            }
            
            $consulta = '
                SELECT m.id_materias, m.nombre, m.semestre
                FROM DocMateria dm
                INNER JOIN Materias m ON dm.id_materias = m.id_materias
                WHERE dm.id_docente = ?
            ';
            $stmt2 = $dbh->prepare($consulta);
            $stmt2->bindParam(1, $id_docente);
            $stmt2->execute();
            $materias = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            
            return $materias;
        }catch(PDOException $e){
            return false;
        }
    }
}



// Se encarga de los eventos
class EventoDocenteDb {
    // Agregar evento
    public function addEvento($titulo, $descripcion, $materia, $semestre, $grupo, $fecha, $username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();

        // Obtener id_docente a partir del username
        $sql = 'SELECT id_docente FROM Docentes WHERE username = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $id_docente = $stmt->fetchColumn();

        if (!$id_docente) {
            throw new Exception("Docente no encontrado");
        }

        $consulta = 'INSERT INTO EventosD (tituloEventoD, descripcionD, id_materia, semestre, grupo, fechaEventoD, id_docente) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $dbh->prepare($consulta);
        return $stmt->execute([$titulo, $descripcion, $materia, $semestre, $grupo, $fecha, $id_docente]);
    }

    // Obtener eventos por docente
    public function getEventosByDocente($username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();

        $sql = 'SELECT e.id_eventoD, e.tituloEventoD, e.descripcionD, e.fechaEventoD, e.semestre, e.grupo, e.id_materia
                FROM EventosD e
                JOIN Docentes d ON d.id_docente = e.id_docente
                WHERE d.username = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Editar evento
    public function updateEvento($id_evento, $titulo, $descripcion, $materia, $semestre, $grupo, $fecha, $username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();

        $sql = 'SELECT id_docente FROM Docentes WHERE username = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$username]);
        $id_docente = $stmt->fetchColumn();
        if (!$id_docente) return false;

        $sql = 'UPDATE EventosD SET 
                    tituloEventoD = ?, 
                    descripcionD = ?, 
                    id_materia = ?, 
                    semestre = ?, 
                    grupo = ?, 
                    fechaEventoD = ?
                WHERE id_eventoD = ? AND id_docente = ?';
        $stmt = $dbh->prepare($sql);
        return $stmt->execute([$titulo, $descripcion, $materia, $semestre, $grupo, $fecha, $id_evento, $id_docente]);
    }

    // Eliminar evento
    public function deleteEvento($id_evento, $username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();

        $sql = 'SELECT id_docente FROM Docentes WHERE username = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$username]);
        $id_docente = $stmt->fetchColumn();
        if (!$id_docente) return false;

        $sql = 'DELETE FROM EventosD WHERE id_eventoD = ? AND id_docente = ?';
        $stmt = $dbh->prepare($sql);
        return $stmt->execute([$id_evento, $id_docente]);
    }
}

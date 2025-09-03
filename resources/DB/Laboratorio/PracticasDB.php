<?php
include_once __DIR__ . '/../conexion.php';

class MateriasLab {
    // Funcion para obtener materias con laboratorio
    public function getMateriaLab() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT * FROM Materias WHERE laboratorio = 1';
            $stmt = $dbh->prepare($consulta);  // <-- Aquí debes pasar la consulta
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al obtener materias con laboratorio: " . $e->getMessage());
            return false;
        }
    }
}

class DocentesLab {
    public function getDocentes() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $sql = 'SELECT id_docente, nombreCompleto FROM Docentes ORDER BY nombreCompleto';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener docentes: " . $e->getMessage());
            return false;
        }
    }
}



class PracticasDb {
    // Agregar práctica
    public function addPractica($id_materia, $id_docente, $id_lab, $fecha, $hora, $grupo, $semestre) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $sql = 'INSERT INTO Practicas (id_materias, id_docente, id_lab, fecha, hora, grupo, semestre)
                    VALUES (?, ?, ?, ?, ?, ?, ?)';
            $stmt = $dbh->prepare($sql);
            return $stmt->execute([$id_materia, $id_docente, $id_lab, $fecha, $hora, $grupo, $semestre]);
        } catch (PDOException $e) {
            error_log("Error al agregar práctica: " . $e->getMessage());
            return false;
        }
    }

    // Obtener prácticas programadas (puedes filtrar por docente, laboratorio, etc. si quieres)
    public function getPracticas() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $sql = 'SELECT p.id_practica, p.fecha, p.hora, p.grupo, p.semestre, 
               p.id_materias, p.id_docente, p.id_lab,
               m.nombre AS materia, d.nombreCompleto AS docente
        FROM Practicas p
        LEFT JOIN Materias m ON p.id_materias = m.id_materias
        LEFT JOIN Docentes d ON p.id_docente = d.id_docente';

            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener prácticas: " . $e->getMessage());
            return false;
        }
    }
    

    // Actualizar práctica
    public function updatePractica($id_practica, $id_materia, $id_docente, $id_lab, $fecha, $hora, $grupo, $semestre) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $sql = 'UPDATE Practicas SET id_materias = ?, id_docente = ?, id_lab = ?, fecha = ?, hora = ?, grupo = ?, semestre = ?
                    WHERE id_practica = ?';
            $stmt = $dbh->prepare($sql);
            return $stmt->execute([$id_materia, $id_docente, $id_lab, $fecha, $hora, $grupo, $semestre, $id_practica]);
        } catch (PDOException $e) {
            error_log("Error al actualizar práctica: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar práctica
    public function deletePractica($id_practica) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $sql = 'DELETE FROM Practicas WHERE id_practica = ?';
            $stmt = $dbh->prepare($sql);
            return $stmt->execute([$id_practica]);
        } catch (PDOException $e) {
            error_log("Error al eliminar práctica: " . $e->getMessage());
            return false;
        }
    }
}
?>

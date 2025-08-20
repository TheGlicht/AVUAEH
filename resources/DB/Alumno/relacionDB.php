<?php
include_once __DIR__ . '/../conexion.php';

class RelacionDb{
    // Funcion para crear las relaciones
    public function createRelation($username, $id_materias){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{   
            $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $id_alumno = $stmt->fetchColumn();
    
            if (!$id_alumno) {
                throw new Exception("Este usuario no está registrado.");
            }

            // Crear relacion
            $consulta = 'INSERT INTO AluMateria(id_alumno, id_materias) VALUES (?, ?)';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $id_alumno);
            $stmt->bindParam(2, $id_materias);
            return $stmt->execute();

        } catch(PDOException $e){
            error_log("Error en showMateria: " . $e->getMessage());
            throw new Exception("Error al obtener las materias: " . $e->getMessage());
        }
    }
    // Funcion para mostrar las relaciones
    public function showRelationbyID($username){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{   
            $sql = 'SELECT id_alumno FROM Alumno WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $id_alumno = $stmt->fetchColumn();
    
            if (!$id_alumno) {
                throw new Exception("Este usuario no está registrado.");
            }

            // Buscar datos de relacion
            $consulta = 'SELECT * FROM AluMateria WHERE id_alumno = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $id_alumno);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            error_log("Error en showMateria: " . $e->getMessage());
            throw new Exception("Error al obtener las materias: " . $e->getMessage());
        }
    }
}
?>
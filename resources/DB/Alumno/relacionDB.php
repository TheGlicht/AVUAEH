<?php
include_once __DIR__ . '/../conexion.php';

class RelacionDb{
    public function createRelation($username,$id_materias){
        $conexion = Conexion::getInstancia()->getDbh();
        try{
            $stmt = $conexion->prepare('SELECT id_alumno FROM Alumno WHERE username=?');
            $stmt->execute([$username]);
            $id_alumno = $stmt->fetchColumn();
            if(!$id_alumno) throw new Exception("Usuario no registrado");

            $stmt = $conexion->prepare('INSERT INTO AluMateria(id_alumno,id_materias,calf_primer, calf_second, calf_ordina) VALUES(?,?,?,?,?)');
            return $stmt->execute([$id_alumno,$id_materias,0,0,0]);
        } catch(PDOException $e){ error_log($e->getMessage()); throw new Exception($e->getMessage()); }
    }

    public function showRelationbyID($username){
        $conexion = Conexion::getInstancia()->getDbh();
        try{
            $stmt = $conexion->prepare('SELECT id_alumno FROM Alumno WHERE username=?');
            $stmt->execute([$username]);
            $id_alumno = $stmt->fetchColumn();
            if(!$id_alumno) throw new Exception("Usuario no registrado");

            $stmt = $conexion->prepare('SELECT M.id_materias, M.nombre,
                                   AM.calf_primer  AS parcial1,
                                   AM.calf_second  AS parcial2,
                                   AM.calf_ordina  AS ordinario
                             FROM AluMateria AM
                             JOIN Materias M ON AM.id_materias=M.id_materias
                             WHERE AM.id_alumno=?');

            $stmt->execute([$id_alumno]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e){ error_log($e->getMessage()); throw new Exception($e->getMessage()); }
    }

    public function updateRelation($username,$id_materias,$p1,$p2,$ord){
        $conexion = Conexion::getInstancia()->getDbh();
        try{
            $stmt = $conexion->prepare('SELECT id_alumno FROM Alumno WHERE username=?');
            $stmt->execute([$username]);
            $id_alumno = $stmt->fetchColumn();
            if(!$id_alumno) throw new Exception("Usuario no registrado");

            $stmt = $conexion->prepare('UPDATE AluMateria SET calf_primer=?, calf_second=?, calf_ordina=? WHERE id_alumno=? AND id_materias=?');
            return $stmt->execute([$p1,$p2,$ord,$id_alumno,$id_materias]);
        } catch(PDOException $e){ error_log($e->getMessage()); throw new Exception($e->getMessage()); }
    }

    public function deleteRelation($username,$id_materias){
        $conexion = Conexion::getInstancia()->getDbh();
        try{
            $stmt = $conexion->prepare('SELECT id_alumno FROM Alumno WHERE username=?');
            $stmt->execute([$username]);
            $id_alumno = $stmt->fetchColumn();
            if(!$id_alumno) throw new Exception("Usuario no registrado");

            $stmt = $conexion->prepare('DELETE FROM AluMateria WHERE id_alumno=? AND id_materias=?');
            return $stmt->execute([$id_alumno,$id_materias]);
        } catch(PDOException $e){ error_log($e->getMessage()); throw new Exception($e->getMessage()); }
    }
}
?>

<?php
include_once __DIR__ . '/../conexion.php';

class PerfilDB {
    // ================== CONTROLADOR PARA TABLA Docentes ======= 
    // Obtener los datos del Docente
    public function getDocente($username){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT id_docente, username, email, nombreCompleto FROM Docentes WHERE username = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->execute([$username]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch (PDOException $e) {
            throw new Exception("Error en getDocente: " . $e->getMessage());
        }
    }

    // Actualizar datos del Docente
    public function updateDocente($username, $nombre, $email){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {            
            $consulta = 'UPDATE Docentes SET username = ?, nombreCompleto = ? WHERE email = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->execute([$username, $nombre, $email]);
            return true;
        } catch(PDOException $e){
            throw new Exception("Error al actualizar datos: " . $e->getMessage());
        }
    }

    // =================== CONTROLADOR PARA TABLA DocMateria =====================
    // Guardar relación Docente - Materia
    public function DocenteMateria($username, $id_materias, $grupo){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $sql = 'SELECT id_docente FROM Docentes WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$username]);
            $id_docente = $stmt->fetchColumn();

            if(!$id_docente) return false;

            // ✅ Validar duplicado
            $check = $dbh->prepare('SELECT COUNT(*) FROM DocMateria WHERE id_docente = ? AND id_materias = ? AND grupo = ?');
            $check->execute([$id_docente, $id_materias, $grupo]);
            if ($check->fetchColumn() > 0) {
                throw new Exception("Ya existe esta materia con ese grupo para este docente.");
            }

            $consulta = 'INSERT INTO DocMateria (id_docente, id_materias, grupo) VALUES (?, ?, ?)';
            $stmt = $dbh->prepare($consulta);
            $stmt->execute([$id_docente, $id_materias, $grupo]);
            return true;
        } catch(PDOException $e){
            return false;
        } catch(Exception $e) {
            return false;
        }        
    }

    

    // Mostrar relaciones Docente - Materia
    public function showDM($username){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $sql = 'SELECT id_docente FROM Docentes WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$username]);
            $id_docente = $stmt->fetchColumn();

            if(!$id_docente) return [];

            $consulta = 'SELECT dm.id_relacion, m.nombre, m.semestre, dm.grupo
                         FROM DocMateria dm
                         INNER JOIN Materias m ON dm.id_materias = m.id_materias
                         WHERE dm.id_docente = ?'; 
            $stmt = $dbh->prepare($consulta);
            $stmt->execute([$id_docente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return [];
        }     
    }

    // Eliminar relación Docente - Materia
    public function DeleteDM($username, $id_relacion){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $sql = 'SELECT id_docente FROM Docentes WHERE username = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$username]);
            $id_docente = $stmt->fetchColumn();
    
            if(!$id_docente) return false;
    
            $consulta = 'DELETE FROM DocMateria WHERE id_docente = ? AND id_relacion = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->execute([$id_docente, $id_relacion]);
            return true;
        } catch(PDOException $e){
            return false;
        }        
    }
    
}
?>

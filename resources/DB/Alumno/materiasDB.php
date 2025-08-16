<?php
include_once __DIR__ . '/../conexion.php';

class MateriaDb {
    //Funcion para mostrar las materias
    public function showMateria(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            // Seleccionar solo los campos necesarios
            $consulta = 'SELECT id_materias, nombre, semestre FROM Materias';
            $stmt = $dbh->prepare($consulta);
            $stmt->execute(); 
            
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Verificar si hay resultados
            if (empty($resultados)) {
                error_log("No se encontraron materias en la base de datos");
            }
            
            return $resultados;
        } catch(PDOException $e){
            error_log("Error en showMateria: " . $e->getMessage());
            throw new Exception("Error al obtener las materias: " . $e->getMessage());
        }
    }


    // Funcion para buscar materias
    public function searchMateria($nombre, $semestre){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'SELECT id_materias, nombre, semestre FROM Materias WHERE nombre = ? OR semestre  = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $semestre);
            $stmt->execute();
            $dbh= null;
        } catch(PODException $e){
            throw new Exception("Error al mostrar los datos: " . $e->getMessage());
        }
    }

    // Funcion para buscar materias con laboratorio 
    public function searchMateriaLab(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'SELECT nombre, semestre FROM Materias WHERE laboratorio = 1';
            $stmt = $dbh->prepare($consulta);
            $stmt->execute();
            $dbh= null;
        } catch(PODException $e){
            throw new Exception("Error al mostrar los datos: " . $e->getMessage());
        }
    }

    // Funcion para relacionar una materia con un alumno
    public function AlumnoMateria(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{

        } catch(PODException $e){
            return false;
        }
    }

    // Funcion para romper dicha relacion con un alumno
    public function BreakAM(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{

        } catch(PODException $e){
            return false;
        }
    }

}

?>
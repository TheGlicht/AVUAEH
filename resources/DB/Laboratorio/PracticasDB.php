<?php
include_once __DIR__ . '/../conexion.php';

class PracticasDb{
    // Agregar practica
    public function addPractica(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $sql = 'S';
        } catch (PDOException $e){
            return false;
        }
    }

    // Obtener practicas programadas
    public function getPracticas(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{

        } catch (PDOException $e){
            return false;
        }
    }

    // Editar practicas
    public function updatePractica(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{

        } catch (PDOException $e){
            return false;
        }
    }

    // Eliminar practicas
    public function deletePractica(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{

        } catch (PDOException $e){
            return false;
        }    
    }
}
?>
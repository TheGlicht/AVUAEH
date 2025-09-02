<?php
include_once __DIR__ . '/../conexion.php';

class MaterialDb{
    // Función para mostrar los materiales
    public function showMaterial(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = 'SELECT * FROM Material';
            $stmt = $dbh->prepare($consulta);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Verificar si hay resultados
            if(empty($resultado)){
                error_log("No se encontraron materiales en la base de datos");                
            }
            return $resultado;
        } catch(PDOException $e){
            error_log("Error en showMaterial: ". $e->getMessage());
            throw new Exception("Error al obtener las materias ". $e->getMessage());
        }
    }

    // Funcion para guardar los materiales
    public function addMaterial($nombre, $tipo, $cantidad, $estado){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = "INSERT INTO Material(nombre, tipo, cantidad, estado) 
                        VALUES (?,?,?,?)";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $tipo);
            $stmt->bindParam(3, $cantidad);
            $stmt->bindParam(4, $estado);
            return $stmt->execute();
        } catch(PDOException $e){
            error_log("Error en addMaterial:" . $e->getMessage());
            return false;
        }
    }

    // Funcion para editar los materiales
    public function updateMaterial($id_material, $nombre, $tipo, $cantidad, $estado){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = "UPDATE Material
            SET nombre=?, tipo=?, cantidad=?, estado=? 
            WHERE id_material = ?";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $tipo);
            $stmt->bindParam(3, $cantidad);
            $stmt->bindParam(4, $estado);
            $stmt->bindParam(5, $id_material);
            return $stmt->execute();
        } catch(PDOException $e){
            error_log("Error en addMaterial:" . $e->getMessage());
            return false;
        }
    }
    // Funcion para eliminar los materiales
    public function deleteMaterial($id_material){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = "DELETE FROM Material WHERE id_material = ?";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $id_material);
            return $stmt->execute();
        } catch(PDOException $e){
            error_log("Error en deleteMaterial: ". $e->getMessage());
            return false;
        }
    }

}

?>
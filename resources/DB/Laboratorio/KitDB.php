<?php
include_once __DIR__ . '/../Conexion.php';

class KitDb {

    // Obtener materias que tienen laboratorio
    public function MateriasLab() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "SELECT id_materias, nombre, semestre 
                        FROM Materias 
                        WHERE laboratorio = 1";
            $stmt = $dbh->prepare($consulta);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en MateriasLab: " . $e->getMessage());
            return [];
        }
    }

    // Funcion para obtener los kits
    public function getAllKitsWithMaterials() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "SELECT k.id_kit, k.nombre AS kit_nombre, m.nombre AS materia_nombre,
                                km.id_material, km.cantidad, mat.nombre AS material_nombre
                         FROM Kit k
                         INNER JOIN Materias m ON k.id_materias = m.id_materias
                         LEFT JOIN KitMaterial km ON k.id_kit = km.id_kit
                         LEFT JOIN Material mat ON km.id_material = mat.id_material
                         ORDER BY k.id_kit";
            $stmt = $dbh->prepare($consulta);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getAllKitsWithMaterials: " . $e->getMessage());
            return [];
        }
    }

    // Agregar un nuevo kit
    public function addKit($nombre, $id_materias) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "INSERT INTO Kit (nombre, id_materias) VALUES (?, ?)";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $id_materias);
            $stmt->execute();
            return $dbh->lastInsertId(); //
        } catch (PDOException $e) {
            error_log("Error en addKit: " . $e->getMessage());
            throw new Exception("Error al crear el kit: " . $e->getMessage());
        }
    }

    // Actualizar un kit existente
    public function updateKit($id_kit, $nombre, $id_materias) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "UPDATE Kit SET nombre=?, id_materias=? WHERE id_kit=?";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $id_materias);
            $stmt->bindParam(3, $id_kit);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en updateKit: " . $e->getMessage());
            return false;
        }
    }

    // Funcion para actualizar los kits y los materiales dentro de
    public function updateKit2($id_kit, $id_material, $cantidad){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $consulta = "UPDATE KitMaterial SET cantidad = ? WHERE id_kit = ? AND id_material = ? ";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $cantidad);
            $stmt->bindParam(2, $id_kit);
            $stmt->bindParam(3, $id_material);
            return $stmt->execute();
        } catch(PDOException $e){
            error_log("Error en updateKit: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar un kit (y sus materiales)
    public function deleteKit($id_kit) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $dbh->beginTransaction();

            // Eliminar materiales asociados
            $stmt = $dbh->prepare("DELETE FROM KitMaterial WHERE id_kit=?");
            $stmt->execute([$id_kit]);

            // Eliminar el kit
            $stmt = $dbh->prepare("DELETE FROM Kit WHERE id_kit=?");
            $stmt->execute([$id_kit]);

            $dbh->commit();
            return true;
        } catch (PDOException $e) {
            $dbh->rollBack();
            error_log("Error en deleteKit: " . $e->getMessage());
            return false;
        }
    }



    
    // ------------------------------------------------------
    // Métodos para manejar la tabla intermedia KitMaterial
    // ------------------------------------------------------

    // Agregar un material a un kit
    public function addMaterialToKit($id_kit, $id_material, $cantidad) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "INSERT INTO KitMaterial (id_kit, id_material, cantidad) VALUES (?, ?, ?)";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $id_kit);
            $stmt->bindParam(2, $id_material);
            $stmt->bindParam(3, $cantidad);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en addMaterialToKit: " . $e->getMessage());
            return false;
        }
    }

    // Listar materiales de un kit
    public function getMaterialsFromKit($id_kit) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "SELECT km.id_material, m.nombre, km.cantidad
                         FROM KitMaterial km
                         INNER JOIN Material m ON km.id_material = m.id_material
                         WHERE km.id_kit = ?";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $id_kit);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getMaterialsFromKit: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar un material de un kit
    public function removeMaterialFromKit($id_kit, $id_material) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "DELETE FROM KitMaterial WHERE id_kit=? AND id_material=?";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $id_kit);
            $stmt->bindParam(2, $id_material);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en removeMaterialFromKit: " . $e->getMessage());
            return false;
        }
    }
}
?>

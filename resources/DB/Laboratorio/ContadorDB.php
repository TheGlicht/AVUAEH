<?php
include_once __DIR__ . '/../conexion.php';

class ContadorDb {

    private function countTable($table, $alias){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try{
            $stmt = $dbh->prepare("SELECT COUNT(*) AS $alias FROM $table");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            // En producción: registra el error en un log
            return [$alias => 0];
        }
    }

    public function contarMaterial(){
        return $this->countTable('Material', 'id_material');
    }

    public function contarSolicitudes(){
        return $this->countTable('ValesA', 'total');
    }

    public function contarPracticas(){ // <- sin 'a' extra
        return $this->countTable('Practicas', 'id_practica');
    }

    public function contarDanos(){ // <- sin ñ
        return $this->countTable('Danos', 'id_dano');
    }
}

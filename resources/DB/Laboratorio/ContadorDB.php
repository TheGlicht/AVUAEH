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

    public function contarPracticas(){
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $stmt = $dbh->prepare("
                SELECT COUNT(*) AS id_practica
                FROM Practicas
                WHERE WEEK(fecha, 1) = WEEK(CURDATE(), 1)  -- misma semana ISO
                  AND YEAR(fecha) = YEAR(CURDATE())        -- mismo año
                  AND DAYOFWEEK(fecha) BETWEEN 2 AND 6     -- lunes (2) a viernes (6)
            ");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log('Error al contar prácticas: ' . $e->getMessage());
            return ['id_practica' => 0];
        }
    }
    

    public function contarDanos(){ // <- sin ñ
        return $this->countTable('Danos', 'id_dano');
    }
}

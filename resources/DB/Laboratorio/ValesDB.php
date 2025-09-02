<?php
// OJO: respeta el nombre EXACTO del archivo de conexión
include_once __DIR__ . '/../Conexion.php';

class ValesDb {

    /* ===========================
     *        SOLICITUDES
     * =========================== */
    public function showSolicitudes() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "SELECT s.id_solicitud, s.estatus, 
                                v.id_vales, v.id_materias, v.id_profesor, 
                                v.diaLab, v.horaLab, v.id_lab, v.id_kit, v.id_alumno
                         FROM Solicitudes s
                         INNER JOIN ValesA v ON s.id_vales = v.id_vales";
            $stmt = $dbh->prepare($consulta);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en showSolicitudes: " . $e->getMessage());
            return false;
        }
    }

    public function addSolicitud($id_vales, $estatus = 1) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "INSERT INTO Solicitudes (id_vales, estatus) VALUES (?, ?)";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $id_vales);
            $stmt->bindParam(2, $estatus);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en addSolicitud: " . $e->getMessage());
            return false;
        }
    }

    public function updateSolicitud($id_solicitud, $estatus) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            if ($estatus == 4) {
                return $this->deleteSolicitud($id_solicitud);
            }
            $consulta = "UPDATE Solicitudes SET estatus=? WHERE id_solicitud=?";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $estatus);
            $stmt->bindParam(2, $id_solicitud);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en updateSolicitud: " . $e->getMessage());
            return false;
        }
    }

    public function deleteSolicitud($id_solicitud) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "DELETE FROM Solicitudes WHERE id_solicitud=?";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $id_solicitud);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en deleteSolicitud: " . $e->getMessage());
            return false;
        }
    }

    /* ===========================
     *           DAÑOS
     * =========================== */
    public function showDanos() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "SELECT * FROM Danos";
            $stmt = $dbh->prepare($consulta);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en showDanos: " . $e->getMessage());
            return false;
        }
    }

    // Si en tu tabla ya existe 'encargado' (NULL por defecto)
    public function addDano($nombreAlu, $numeroCuenta, $id_material, $id_laboratorio, $fechaLimite, $estatus = 0, $encargado = null) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "INSERT INTO Danos (nombreAlu, numeroCuenta, id_material, id_laboratorio, fechaLimite, estatus, encargado)
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $nombreAlu);
            $stmt->bindParam(2, $numeroCuenta);
            $stmt->bindParam(3, $id_material);
            $stmt->bindParam(4, $id_laboratorio);
            $stmt->bindParam(5, $fechaLimite);
            $stmt->bindParam(6, $estatus);
            $stmt->bindParam(7, $encargado);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en addDano: " . $e->getMessage());
            return false;
        }
    }

    public function updateDano($id_dano, $estatus) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "UPDATE Danos SET estatus=? WHERE id_dano=?";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $estatus);
            $stmt->bindParam(2, $id_dano);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en updateDano: " . $e->getMessage());
            return false;
        }
    }

    public function deleteReporte($id_dano) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "DELETE FROM Danos WHERE id_dano=?";
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $id_dano, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en deleteReporte (ValesDB): " . $e->getMessage());
            return false;
        }
    }
    

    // === Materiales (para llenar el <select>) ===
    public function getMateriales() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "SELECT id_material, nombre FROM Material";
            $stmt = $dbh->prepare($consulta);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getMateriales: " . $e->getMessage());
            return false;
        }
    }
}
?>

<?php
include_once __DIR__ . '/../conexion.php';

class GrupoDb {
    // Traer alumnos por semestre y grupo
    public function showAlumnosSG() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = "SELECT d.id_DatosA, d.nombreCompleto, d.semestre, d.grupo, a.id_alumno, a.username, a.email
                         FROM DatosA d
                         INNER JOIN Alumno a ON d.id_alumno = a.id_alumno
                         ORDER BY d.semestre, d.grupo, d.nombreCompleto";
            $stmt = $dbh->prepare($consulta);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
}

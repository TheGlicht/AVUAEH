<?php
include_once __DIR__ . '/../conexion.php';

class GrupoDb {
    /**
     * Devuelve alumnos que:
     *  - están inscritos en una materia (AluMateria.id_materias)
     *  - esa materia es impartida por el docente (DocMateria.id_materias)
     *  - el alumno tiene DatosA.semestre = Materias.semestre
     *  - el alumno tiene DatosA.grupo = DocMateria.grupo
     *
     * @param string $username Username del docente (session)
     * @return array
     */
    public function showAlumnosSG(string $username) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            // 1) obtener id_docente
            $sql = "SELECT id_docente FROM Docentes WHERE username = ?";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$username]);
            $id_docente = $stmt->fetchColumn();

            if (!$id_docente) {
                return [];
            }

            // 2) consulta: alumnos que están en la materia que el docente imparte
            //    y además coinciden en semestre (con la materia) y en grupo (con DocMateria.grupo)
            $consulta = "
                SELECT DISTINCT
                    a.id_alumno,
                    a.username,
                    a.email,
                    d.id_DatosA,
                    d.nombreCompleto,
                    d.semestre,
                    d.grupo,
                    m.id_materias,
                    m.nombre AS materiaNombre
                FROM DocMateria dm
                INNER JOIN Materias m ON dm.id_materias = m.id_materias
                INNER JOIN AluMateria am ON am.id_materias = m.id_materias
                INNER JOIN Alumno a ON a.id_alumno = am.id_alumno
                INNER JOIN DatosA d ON d.id_alumno = a.id_alumno
                WHERE dm.id_docente = ?
                  AND d.semestre = m.semestre     -- alumno en el semestre que corresponde a la materia
                  AND d.grupo = dm.grupo          -- alumno en el grupo que dicta el docente para esa materia
                ORDER BY m.nombre, d.semestre, d.grupo, d.nombreCompleto
            ";

            $stmt2 = $dbh->prepare($consulta);
            $stmt2->execute([$id_docente]);
            $resultados = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        } catch (PDOException $e) {
            error_log("showAlumnosSG error: " . $e->getMessage());
            return [];
        }
    }
}

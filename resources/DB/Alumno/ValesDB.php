<?php
include_once __DIR__ . '/../conexion.php';

class ValesDb {
    private $conn;
    private $username;

    public function __construct($conn, $username) {
        $this->conn = $conn;
        $this->username = $username;
    }

    //  Obtener id_alumno a partir del username actual
    private function getAlumnoId() {
        $sql = "SELECT id_alumno FROM Alumno WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->username]);
        $id_alumno = $stmt->fetchColumn();
        return $id_alumno ?: false;
    }

    //  Crear vale
    public function agregarVale($id_materias, $id_docente, $diaLab, $horaLab, $id_lab, $id_kit) {
        $id_alumno = $this->getAlumnoId();
        if (!$id_alumno) return false;

        $sql = "INSERT INTO ValesA (id_materias, id_docente, diaLab, horaLab, id_lab, id_kit, id_alumno) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_materias, $id_docente, $diaLab, $horaLab, $id_lab, $id_kit, $id_alumno]);
    }

    //  Obtener vales por alumno
    public function obtenerValesPorAlumno() {
        $id_alumno = $this->getAlumnoId();
        if (!$id_alumno) return [];

        $sql = "SELECT v.id_vales, m.nombre AS materia, d.nombreCompleto AS docente,
                       v.diaLab, v.horaLab, v.id_lab, k.nombre AS kit
                FROM ValesA v
                INNER JOIN Materias m ON v.id_materias = m.id_materias
                INNER JOIN Docentes d ON v.id_docente = d.id_docente
                INNER JOIN Kit k ON v.id_kit = k.id_kit
                WHERE v.id_alumno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_alumno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Eliminar vale
    public function eliminarVale($id_vales) {
        $id_alumno = $this->getAlumnoId();
        if (!$id_alumno) return false;

        $sql = "DELETE FROM ValesA WHERE id_vales = ? AND id_alumno = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_vales, $id_alumno]);
    }

    //  Obtener materias que tienen laboratorio
    public function obtenerMaterias() {
        $sql = "SELECT id_materias, nombre FROM Materias WHERE laboratorio = 1";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Obtener docentes que han tenido relación con una materia
    public function obtenerDocentesPorMateria($id_materia) {
        $sql = "SELECT DISTINCT d.id_docente, d.nombreCompleto
                FROM Docentes d
                INNER JOIN ValesA v ON v.id_docente = d.id_docente
                WHERE v.id_materias = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Obtener kits de una materia
    public function obtenerKitsPorMateria($id_materia) {
        $sql = "SELECT id_kit, nombre FROM Kit WHERE id_materias = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

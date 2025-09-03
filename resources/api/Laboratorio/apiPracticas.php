<?php
session_start();
require_once __DIR__ . '/../../DB/Laboratorio/PracticasDB.php';

if (!isset($_SESSION['username'])) {
    die("Acceso no autorizado");
}

$practicasDb = new PracticasDb();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    
    switch ($action) {
        case 'listar':
            $practicas = $practicasDb->getPracticas();
            if (!$practicas) {
                $practicas = [];
            }
            header('Content-Type: application/json');
            echo json_encode($practicas);
            break;
        
        case 'agregar':
            $id_materia = $_POST['materiaKit'] ?? null;
            $id_docente = $_POST['docenteLab'] ?? null;
            $id_lab = $_POST['laboratorio'] ?? null; // Aquí debes mapear el valor del select a id_lab real
            $fecha = $_POST['fecha'] ?? null;
            $hora = $_POST['hora'] ?? null;
            $grupo = $_POST['grupo'] ?? null;
            $semestre = $_POST['semestre'] ?? null;

            if ($practicasDb->addPractica($id_materia, $id_docente, $id_lab, $fecha, $hora, $grupo, $semestre)) {
                echo "OK";
            } else {
                echo "ERROR";
            }
            break;

        case 'editar':
            $id_practica = $_POST['id_practica'] ?? null;
            $id_materia = $_POST['materiaKit'] ?? null;
            $id_docente = $_POST['docenteLab'] ?? null;
            $id_lab = $_POST['laboratorio'] ?? null;
            $fecha = $_POST['fecha'] ?? null;
            $hora = $_POST['hora'] ?? null;
            $grupo = $_POST['grupo'] ?? null;
            $semestre = $_POST['semestre'] ?? null;

            if ($practicasDb->updatePractica($id_practica, $id_materia, $id_docente, $id_lab, $fecha, $hora, $grupo, $semestre)) {
                echo "OK";
            } else {
                echo "ERROR";
            }
            break;

        case 'eliminar':
            $id_practica = $_POST['id_practica'] ?? null;
            if ($practicasDb->deletePractica($id_practica)) {
                echo "OK";
            } else {
                echo "ERROR";
            }
            break;

        default:
            echo "Acción no válida";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>

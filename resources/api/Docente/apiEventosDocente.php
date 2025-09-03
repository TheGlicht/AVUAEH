<?php
session_start();
require_once __DIR__ . '/../../DB/Docente/eventosDB.php';

if(!isset($_SESSION['username'])) {
    die("Acceso no autorizado");
}

$eventoDb = new EventoDocenteDb();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'listar':
            $eventos = $eventoDb->getEventosByDocente($_SESSION['username']);
            foreach ($eventos as $ev) {
                echo "<tr data-description='".htmlspecialchars($ev['descripcionD'], ENT_QUOTES)."'>
                        <td>{$ev['tituloEventoD']}</td>
                        <td>{$ev['fechaEventoD']}</td>
                        <td>
                            <button class='btn btn-warning btn-sm me-1 edit-btn' data-id='{$ev['id_eventoD']}' title='Editar'>
                                <i class='fa-solid fa-pen-to-square'></i>
                            </button>
                            <button class='btn btn-danger btn-sm delete-btn' data-id='{$ev['id_eventoD']}' title='Eliminar'>
                                <i class='fa-solid fa-trash'></i>
                            </button>
                        </td>
                      </tr>";
            }
            break;

        case 'agregar':
            $titulo = $_POST['titulo'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $materia = $_POST['materia'] ?? null;
            $semestre = $_POST['semestre'] ?? null;
            $grupo = $_POST['grupo'] ?? null;
            $fecha = $_POST['fecha'] ?? '';

            if($eventoDb->addEvento($titulo, $descripcion, $materia, $semestre, $grupo, $fecha, $_SESSION['username'])) {
                echo "OK";
            } else {
                echo "ERROR";
            }
            break;

        case 'eliminar':
            $id = $_POST['id_evento'] ?? '';
            if($eventoDb->deleteEvento($id, $_SESSION['username'])){
                echo "OK";
            } else {
                echo "ERROR: No se pudo eliminar el evento";
            }
            break;

        case 'editar':
            $id = $_POST['id_evento'] ?? '';
            $titulo = $_POST['titulo'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $materia = $_POST['materia'] ?? null;
            $semestre = $_POST['semestre'] ?? null;
            $grupo = $_POST['grupo'] ?? null;
            $fecha = $_POST['fecha'] ?? '';

            if($eventoDb->updateEvento($id, $titulo, $descripcion, $materia, $semestre, $grupo, $fecha, $_SESSION['username'])) {
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

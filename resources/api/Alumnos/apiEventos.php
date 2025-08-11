<?php
session_start();
require_once __DIR__ . '/../../DB/Alumno/eventosDB.php';

// Verificar autenticación
if(!isset($_SESSION['username'])) {
    die("Acceso no autorizado");
}

$eventoDb = new EventoDb();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'listar':
            $eventos = $eventoDb->getEventosByEmail($_SESSION['username']); // Asegúrate de que esto esté correcto
            foreach ($eventos as $ev) {
                echo "<tr>
                        <td>{$ev['tituloEvento']}</td>
                        <td>{$ev['fechaEvento']}</td>
                        <td>
                            <button class='btn btn-warning btn-sm me-1 edit-btn' data-id='{$ev['id_evento']}' title='Editar'>
                                <i class='fa-solid fa-pen-to-square'></i>
                            </button>
                            <button class='btn btn-danger btn-sm delete-btn' data-id='{$ev['id_evento']}' title='Eliminar'>
                                <i class='fa-solid fa-trash'></i>
                            </button>
                        </td>
                      </tr>";
            }
            break;
        

            case 'agregar':
                $titulo = $_POST['titulo'] ?? '';
                $descripcion = $_POST['descripcion'] ?? '';
                $fecha = $_POST['fecha'] ?? '';
                // Cambia $_SESSION['username'] por el username que se está utilizando
                if($eventoDb->addEvento($titulo, $descripcion, $fecha, $_SESSION['username'])) {
                    echo "OK";
                } else {
                    echo "ERROR";
                }
                break;

        case 'eliminar':
            $id = $_POST['id_evento'] ?? '';
            if($eventoDb->deleteEvento($id, $_SESSION['username'])) {
                echo "OK";
            } else {
                echo "ERROR";
            }
            break;

        case 'editar':
            $id = $_POST['id_evento'] ?? '';
            $titulo = $_POST['titulo'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            if($eventoDb->updateEvento($id, $titulo, $descripcion, $fecha, $_SESSION['username'])) {
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

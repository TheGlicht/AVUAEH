<?php
session_start();

// Ajusta la ruta según donde esté tu materiasDB.php
require_once dirname(__DIR__, 3) . '/resources/DB/Alumno/materiasDB.php';

if(!isset($_SESSION['username'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

$materiaDb = new MateriaDb();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch($action){
        case 'mostrarM':
            $materias = $materiaDb->showMateria();
            
            if (empty($materias)) {
                echo "<option value=''>No hay materias disponibles</option>";
                exit;
            }
            
            foreach($materias as $materia) {
                echo '<option value="' . htmlspecialchars($materia['id_materia']) . '">'
                     . htmlspecialchars($materia['nombre']) . ' - Semestre ' 
                     . htmlspecialchars($materia['semestre']) . '</option>';
            }
            break;

        default:
            header('HTTP/1.1 400 Bad Request');
            echo "<option value=''>Acción no válida</option>";
            break;
    }
} catch(Exception $e){
    header('HTTP/1.1 500 Internal Server Error');
    echo "<option value=''>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
}
?>

<?php
session_start();
require_once __DIR__ . '/../../DB/Docente/PerfilDB.php';

if(!isset($_SESSION['username'])) die("Acceso no autorizado");

$perfilDb = new PerfilDB();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch($action) {
        // === Perfil Docente ===
        case 'mostrar':
            $data = $perfilDb->getDocente($_SESSION['username']);
            echo json_encode($data);
            break;

        case 'actualizar':
            $username = $_POST['username'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $ok = $perfilDb->updateDocente($username, $nombre, $email);
            echo json_encode(["success"=>$ok]);
            break;

        // === Relación Docente-Materia ===
        case 'listar':
            $relaciones = $perfilDb->showDM($_SESSION['username']);
            echo json_encode($relaciones);
            break;

            case 'agregar':
                $id_materias = $_POST['id_materias'] ?? 0;
                $grupo = $_POST['grupo'] ?? 0;
                try {
                    $ok = $perfilDb->DocenteMateria($_SESSION['username'], $id_materias, $grupo);
                    echo json_encode(["success"=>$ok]);
                } catch(Exception $e) {
                    echo json_encode(["success"=>false, "error"=>$e->getMessage()]);
                }
                break;
            
            
            case 'eliminar':
                $id_relacion = $_POST['id_relacion'] ?? 0;
                $ok = $perfilDb->DeleteDM($_SESSION['username'], $id_relacion);
                echo json_encode(["success"=>$ok]);
                break;
            

        default:
            echo json_encode(["error"=>"Acción no válida"]);
    }
} catch(Exception $e) {
    echo json_encode(["error"=>$e->getMessage()]);
}
?>

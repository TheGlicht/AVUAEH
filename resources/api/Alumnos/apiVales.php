<?php
session_start();
include_once __DIR__ . '/../../DB/conexion.php';
include_once __DIR__ . '/../../DB/Alumno/ValesDB.php';

header('Content-Type: application/json');

$currentUsername = $_SESSION['username'] ?? null;
if (!$currentUsername) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit;
}

$db = new ValesDb($conn, $currentUsername);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST': // Crear vale
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data['id_materias']) && !empty($data['id_docente']) && !empty($data['diaLab']) &&
            !empty($data['horaLab']) && !empty($data['id_lab']) && !empty($data['id_kit'])) {
            
            $ok = $db->agregarVale(
                $data['id_materias'],
                $data['id_docente'],
                $data['diaLab'],
                $data['horaLab'],
                $data['id_lab'],
                $data['id_kit']
            );
            echo json_encode(["success" => $ok]);
        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    case 'GET': // Listar info
        $action = $_GET['action'] ?? 'vales';

        switch ($action) {
            case 'vales':
                echo json_encode($db->obtenerValesPorAlumno());
                break;
            case 'materias':
                echo json_encode($db->obtenerMaterias());
                break;
            case 'docentes':
                $id_materia = $_GET['id_materia'] ?? null;
                echo json_encode($id_materia ? $db->obtenerDocentesPorMateria($id_materia) : []);
                break;
            case 'kits':
                $id_materia = $_GET['id_materia'] ?? null;
                echo json_encode($id_materia ? $db->obtenerKitsPorMateria($id_materia) : []);
                break;
            default:
                echo json_encode(["error" => "Acción no válida"]);
        }
        break;

    case 'DELETE': // Eliminar vale
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data['id_vales'])) {
            $ok = $db->eliminarVale($data['id_vales']);
            echo json_encode(["success" => $ok]);
        } else {
            echo json_encode(["error" => "Falta id_vales"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no soportado"]);
        break;
}

<?php
include_once __DIR__ . '/../../DB/Laboratorio/MaterialDB.php';
session_start();
header('Content-Type: application/json');

// Verifica que el usuario haya iniciado sesión
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No has iniciado sesión.']);
    exit;
}

$materialDB = new MaterialDb();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET': // Mostrar materiales
            $materiales = $materialDB->showMaterial();
            echo json_encode($materiales);
            break;

        case 'POST': // Agregar material
            $data = json_decode(file_get_contents("php://input"), true);
            if ($materialDB->addMaterial($data['nombre'], $data['tipo'], $data['cantidad'], $data['estado'])) {
                echo json_encode(['success' => true, 'message' => 'Material agregado']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Error al agregar']);
            }
            break;

        case 'PUT': // Actualizar material
            $data = json_decode(file_get_contents("php://input"), true);
            if ($materialDB->updateMaterial($data['id_material'], $data['nombre'], $data['tipo'], $data['cantidad'], $data['estado'])) {
                echo json_encode(['success' => true, 'message' => 'Material actualizado']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Error al actualizar']);
            }
            break;

        case 'DELETE': // Eliminar material
            $data = json_decode(file_get_contents("php://input"), true);
            if ($materialDB->deleteMaterial($data['id_material'])) {
                echo json_encode(['success' => true, 'message' => 'Material eliminado']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Error al eliminar']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

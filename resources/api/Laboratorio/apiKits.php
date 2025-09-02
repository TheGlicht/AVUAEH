<?php
include_once __DIR__ . '/../../DB/Laboratorio/KitDB.php';
include_once __DIR__ . '/../../DB/Conexion.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No has iniciado sesión']);
    exit;
}

$kitDB = new KitDb();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['materias'])) {
                echo json_encode($kitDB->MateriasLab());
            } elseif (isset($_GET['completo'])) {
                echo json_encode($kitDB->getAllKitsWithMaterials());
            } else {
                echo json_encode($kitDB->getAllKitsWithMaterials());
            }
            break;
        

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $nombre = $data['nombre'];
            $id_materias = $data['id_materias'];
            $materiales = $data['materiales'];

            $dbh = Conexion::getInstancia()->getDbh();
            $dbh->beginTransaction();

            $kitDB->addKit($nombre, $id_materias);
            $id_kit = $dbh->lastInsertId();

            foreach ($materiales as $m) {
                $kitDB->addMaterialToKit($id_kit, $m['id_material'], $m['cantidad']);
            }

            $dbh->commit();
            echo json_encode(['success' => true]);
            break;
        
            case 'PUT':
                $data = json_decode(file_get_contents("php://input"), true);
                $id_kit = $data['id_kit'];
                $nombre = $data['nombre'];
                $id_materias = $data['id_materias'];
                $materiales = $data['materiales'];
            
                $dbh = Conexion::getInstancia()->getDbh();
                $dbh->beginTransaction();
            
                // Actualizar info del kit
                $kitDB->updateKit2($id_kit, $id_material, $cantidad);
            
                // Limpiar materiales viejos
                $stmt = $dbh->prepare("DELETE FROM KitMaterial WHERE id_kit=?");
                $stmt->execute([$id_kit]);
            
                // Insertar materiales nuevos
                foreach ($materiales as $m) {
                    $kitDB->addMaterialToKit($id_kit, $m['id_material'], $m['cantidad']);
                }
            
                $dbh->commit();
                echo json_encode(['success' => true]);
                break;
            
            case 'DELETE':
                parse_str(file_get_contents("php://input"), $data);
                $id_kit = $data['id_kit'];
            
                if ($kitDB->deleteKit($id_kit)) {
                    echo json_encode(['success' => true]);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'No se pudo eliminar el kit']);
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

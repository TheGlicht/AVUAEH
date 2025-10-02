<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *"); // Ajusta según tu dominio
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include_once __DIR__ . '/../../DB/Laboratorio/ValesDB.php';
include_once __DIR__ . '/../../DB/Conexion.php';
session_start();

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No has iniciado sesión']);
    exit;
}

$valesDb = new ValesDb();

// Detectar método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Manejar preflight OPTIONS
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    switch ($method) {
        case 'GET':
            // Obtener materiales
            if (isset($_GET['materiales'])) {
                $materiales = $valesDb->getMateriales();
                echo json_encode($materiales ?: []);
                exit;
            }

            // Obtener daños
            $danos = $valesDb->showDanos();
            echo json_encode($danos ?: []);
            exit;

        case 'POST':
            $inputRaw = file_get_contents("php://input");
            $input = json_decode($inputRaw, true);

            if ($input === null) {
                throw new Exception("JSON inválido o vacío: " . $inputRaw);
            }           

            // --- AGREGAR REPORTE ---
            $alumnos = $input['alumnos'] ?? [];
            $materiales = $input['materiales'] ?? [];
            $fechaLimite = $input['fechaLimite'] ?? null;
            $id_laboratorio = $input['id_laboratorio'] ?? 1;
            $encargado = $input['encargado'] ?? null;
            $estatus = 0;

            if (!$fechaLimite || empty($alumnos) || empty($materiales)) {
                throw new Exception("Faltan datos obligatorios para insertar reporte.");
            }

            $success = true;

            foreach ($alumnos as $al) {
                foreach ($materiales as $mat) {
                    $res = $valesDb->addDano(
                        $al['nombre'],
                        $al['numeroCuenta'],
                        $mat['id_material'],
                        $id_laboratorio,
                        $fechaLimite,
                        $estatus,
                        $encargado
                    );
                    if ($res) {
                        // Actualizar stock dañado
                        $valesDb->aumentarDanado($mat['id_material'], $mat['cantidad']);
                    }
                    if (!$res) $success = false;
                }
            }
            

            echo json_encode(['success' => $success]);
            exit;

        case 'PUT':
            $input = json_decode(file_get_contents("php://input"), true);
            if (!isset($input['id_dano']) || !isset($input['estatus'])) {
                throw new Exception("Faltan datos para actualizar.");
            }

            $res = $valesDb->updateDano($input['id_dano'], $input['estatus']);
            echo json_encode(['success' => $res]);
            exit;

        case 'DELETE':
            $input = json_decode(file_get_contents("php://input"), true);
            if ($input === null) {
                parse_str(file_get_contents("php://input"), $input);
            }

            $id = $input['id_dano'] ?? null;

            if ($id) {
                $success = $valesDb->deleteReporte($id);
                echo json_encode(["success" => $success]);
                exit;
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "error" => "ID no recibido"]);
                exit;
            }

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            exit;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}

<?php
session_start();
require_once __DIR__ . '/../../DB/conexion.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

$action = $_REQUEST['action'] ?? '';
$conexion = Conexion::getInstancia()->getDbh();

try {
    if ($action === 'enviar') {
        $receptor = $_POST['receptor'] ?? '';
        $mensaje  = trim($_POST['mensaje'] ?? '');

        if (!$receptor || !$mensaje) {
            echo json_encode(['error' => 'Datos incompletos']);
            exit;
        }

        $sql = "INSERT INTO MensajesChat (emisor, receptor, mensaje) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $ok = $stmt->execute([$_SESSION['username'], $receptor, $mensaje]);

        if ($ok) {
            echo json_encode(['status' => 'OK']);
        } else {
            echo json_encode(['error' => 'No se pudo insertar el mensaje']);
        }
        exit;
    }

    if ($action === 'cargar') {
        $contacto = $_GET['contacto'] ?? '';
        if (!$contacto) {
            echo json_encode([]);
            exit;
        }

        $sql = "SELECT emisor, mensaje, fecha 
                FROM MensajesChat 
                WHERE (emisor = ? AND receptor = ?) OR (emisor = ? AND receptor = ?)
                ORDER BY fecha ASC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$_SESSION['username'], $contacto, $contacto, $_SESSION['username']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
        exit;
    }

    echo json_encode(['error' => 'Accion no valida']);
} catch (PDOException $e) {
    error_log("apiChat error: " . $e->getMessage());
    echo json_encode(['error' => 'Error servidor: ' . $e->getMessage()]);
}

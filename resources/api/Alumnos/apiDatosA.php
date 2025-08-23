<?php
session_start();
require_once __DIR__ . '/../../DB/Alumno/alumnosDB.php';

// Verificar autenticación
if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Acceso no autorizado"]);
    exit;
}

$alumnosDb = new DataAlumnoDb();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

header('Content-Type: application/json');

try {
    switch ($action) {
        case 'mostrar': {
            // Trae datos (LEFT JOIN): si no hay en DatosA, regresa username y nulls
            $data = $alumnosDb->getADatos($_SESSION['username']);
            if ($data && is_array($data)) {
                echo json_encode(["success" => true, "data" => $data]);
            } else {
                // Sin registro en DatosA; devolvemos al menos el username de sesión
                echo json_encode([
                    "success" => true,
                    "data" => [
                        "nombreCompleto" => "",
                        "semestre"       => "",
                        "grupo"          => "",
                        "username"       => $_SESSION['username']
                    ],
                    "message" => "Aún no hay datos de perfil, completa y guarda."
                ]);
            }
            break;
        }

        case 'editar': {
            // Datos recibidos del formulario
            $nombre       = trim($_POST['nombreCompleto'] ?? '');
            $semestreRaw  = $_POST['semestre'] ?? '';
            $grupoRaw     = $_POST['grupo'] ?? '';
            $newUsername  = trim($_POST['username'] ?? '');

            // Validación básica
            if ($nombre === '' || $semestreRaw === '' || $grupoRaw === '' || $newUsername === '') {
                echo json_encode(["success" => false, "message" => "Datos incompletos"]);
                break;
            }

            $semestre = (int)$semestreRaw;
            $grupo    = (int)$grupoRaw;
            if ($semestre < 1 || $semestre > 6 || $grupo < 1) {
                echo json_encode(["success" => false, "message" => "Valores de semestre/grupo inválidos"]);
                break;
            }

            $currentUsername = $_SESSION['username'];

            $ok = $alumnosDb->upsertADatos($nombre, $semestre, $grupo, $newUsername, $currentUsername);

            if ($ok === true) {
                // Si se cambió el username, actualizamos la sesión
                $_SESSION['username'] = $newUsername;
                echo json_encode(["success" => true, "message" => "Perfil actualizado correctamente"]);
            } else {
                // $ok puede ser string con el mensaje de error (por ejemplo, username duplicado)
                $msg = is_string($ok) ? $ok : "No se pudo actualizar el perfil";
                echo json_encode(["success" => false, "message" => $msg]);
            }
            break;
        }

        default:
            echo json_encode(["success" => false, "message" => "Acción no válida"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "ERROR: " . $e->getMessage()]);
}

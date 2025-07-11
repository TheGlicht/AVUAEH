<?php
// Encabezados respuesta JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Incluir clases necesarias
require_once '../../DB/conexion.php';
require_once '../../DB/Alumno/alumnosDB.php';

// Obtener datos del cuerpo de la petición
$data = json_decode(file_get_contents("php://intput"), true);

// Validaciones básicas
if(
    !isset($data['username']) || empty(trim($data['username'])) ||
    !isset($data['email']) || empty(trim($data['email'])) ||
    !isset($data['pass']) || empty(trim($data['pass']))
){
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "mensaje" => "Faltan datos requeridos (username, email o contraseña)"
    ]);
    exit;
}

// Sincronizar y validar datos
$username = htmlspecialchars(trim($data['username']));
$email = filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL);
$pass = trim($data['pass']);

if(!$email){
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "mensaje" => "Correo electrónico no válido"
    ]);
    exit;
}

// Encriptar contraseña (opcional, pero muy recomendable)
// Originalmente es con PASSWORD_DEFAULT
$hashedPass = password_hash($pass, PASSWORD_BCRYPT); 

// Insertar en base de datos
$alumno = new AlumnoDb();
try{
    $alumno->addAlumno($username, $email, $hashedPass);
    echo json_encode([
        "status" => "success",
        "mensaje" => "Usuario registrado correctamente"
    ]);
} catch (Exception $e){
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al registrar el usuario"
    ]);
}

?>
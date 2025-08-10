<?php
include_once __DIR__ . '/../../DB/Alumno/alumnosDB.php';
session_start();
header('Content-Type: application/json');

// Verifica que el usuario haya iniciado sesión
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No has iniciado sesión.']);
    exit;
}

// Crea una instancia y obtiene al alumno
$alumnoDB = new AlumnoDb();
$usuario = $alumnoDB->showAlumno($_SESSION['username']);

if ($usuario) {
    echo json_encode($usuario);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Usuario no encontrado.']);
}

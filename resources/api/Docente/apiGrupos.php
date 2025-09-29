<?php
session_start();
require_once __DIR__ .'/../../DB/Docente/gruposDB.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['username'])) {
    echo json_encode([]);
    exit;
}

$gruposDb = new GrupoDb();
$alumnos = $gruposDb->showAlumnosSG($_SESSION['username']);

echo json_encode($alumnos, JSON_UNESCAPED_UNICODE);

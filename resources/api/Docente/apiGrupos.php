<?php
session_start();
require_once __DIR__ .'/../../DB/Docente/gruposDB.php';

header('Content-Type: application/json; charset=utf-8');

$gruposDb = new GrupoDb();
$alumnos = $gruposDb->showAlumnosSG();

echo json_encode($alumnos);
?>
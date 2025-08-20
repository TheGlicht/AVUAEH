<?php
session_start();
require_once __DIR__ . '/../../DB/Alumno/relacionDB.php';

if(!isset($_SESSION['username'])) die("Acceso no autorizado");

$relacionDb = new RelacionDb();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch($action){
        case 'listar':
            $relaciones = $relacionDb->showRelationbyID($_SESSION['username']);
            echo json_encode($relaciones);
            break;

        case 'agregar':
            $id_materias = $_POST['id_materias'];
            echo $relacionDb->createRelation($_SESSION['username'], $id_materias) ? "OK":"ERROR";
            break;

        case 'actualizar':
            $id_materias = $_POST['id_materias'];
            $p1 = $_POST['parcial1'] ?? 0;
            $p2 = $_POST['parcial2'] ?? 0;
            $ord = $_POST['ordinario'] ?? 0;
            echo $relacionDb->updateRelation($_SESSION['username'],$id_materias,$p1,$p2,$ord)?"OK":"ERROR";
            break;

        case 'eliminar':
            $id_materias = $_POST['id_materias'];
            echo $relacionDb->deleteRelation($_SESSION['username'],$id_materias)?"OK":"ERROR";
            break;
    }
} catch(Exception $e){
    echo "ERROR: ".$e->getMessage();
}
?>

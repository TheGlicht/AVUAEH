<?php
session_start();
require_once __DIR__ . '/../../DB/Alumno/relacionDB.php';

// Verificar autenticación
if(!isset($_SESSION['username'])) {
    die("Acceso no autorizado");
}

$relacionDb = new RelacionDb();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try{
    switch ($action) {
    // Evento para mostrar eventos en la tabla
    case 'listar':
        $relacion = $relacionDb->showRelationbyID($_SESSION['username']);
        // Aqui va la el como se colocaran en la tabla asiganda
       
        break;
    // Evento para crear las relaciones
    case 'agregar':
        $username = $_POST['username'];
        $id_materias = $_POST['id_materias'];
        if($relacionDb->createRelation($username, $id_materias)){
           echo "OK" ;
        } else {
            echo "ERROR";
        }
        break;
    }
}catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>

<?php
require_once("../../../DB/Alumno/datosDB.php");
session_start();

$alumnoDb = new Alumno();

if (!isset($_GET['action'])) {
    echo "Acción no especificada";
    exit;
}

$action = $_GET['action'];

switch ($action) {
    case 'mostrar':
        $result = $alumnoDb->getADatos($_SESSION['username']);
        if ($result && count($result) > 0) {
            // Retorna datos en texto plano separados por |
            $row = $result[0];
            echo $row['nombreCompleto'] . "|" . $row['username'] . "|" . $row['semestre'] . "|" . $row['grupo'];
        } else {
            echo "NO_DATOS";
        }
        break;

    case 'editar':
        // Validar campos
        if (empty($_POST['nombreCompleto']) || empty($_POST['username']) || empty($_POST['semestre']) || empty($_POST['grupo'])) {
            echo "ERROR:Campos incompletos";
            exit;
        }

        // Verificar si ya existe
        $existe = $alumnoDb->getADatos($_SESSION['username']);

        if ($existe && count($existe) > 0) {
            // Actualizar
            $result = $alumnoDb->updateADatos(
                trim($_POST['nombreCompleto']),
                intval($_POST['semestre']),
                intval($_POST['grupo']),
                trim($_POST['username'])
            );
            echo $result ? "OK_UPDATE" : "ERROR_UPDATE";
        } else {
            // Insertar
            $result = $alumnoDb->addADatos(
                trim($_POST['nombreCompleto']),
                intval($_POST['semestre']),
                intval($_POST['grupo']),
                $_SESSION['username']
            );
            echo $result ? "OK_INSERT" : "ERROR_INSERT";
        }
        break;

    default:
        echo "Acción no válida";
}

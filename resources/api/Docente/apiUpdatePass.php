<?php
include_once __DIR__ . '/../../DB/Docente/docenteDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'], $_POST['password'])) {
        $email = trim($_POST['email']);
        $pass = $_POST['password'];

        if (!empty($email) && !empty($pass)) {
            try {
                $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
                $docente = new DocenteDb();
                $docente->updatePasDocente($email, $hashedPass);

                echo "Actualización exitosa.";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Todos los campos son obligatorios.";
        }
    } else {
        echo "Faltan datos del formulario.";
    }
} else {
    echo "Método no permitido.";
}
?>
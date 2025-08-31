<?php
include_once __DIR__ . '/../../DB/Docente/docenteDB.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            $docente = new DocenteDb();
            $hashedPasswordFromDB = $docente->getPasswordByEmail($email);

            if ($hashedPasswordFromDB && password_verify($password, $hashedPasswordFromDB)) {
                $username = $docente->getUsernameByEmail($email); 

                if ($username) {
                    $_SESSION['username'] = $username; 
                    $_SESSION['email'] = $email;      
                    echo "Inicio de sesión exitoso";
                } else {
                    echo "Error al obtener el usuario.";
                }
            } else {
                echo "Correo o contraseña incorrectos.";
            }
        } catch (Exception $e) {
            echo "Error en el servidor: " . $e->getMessage();
        }
    } else {
        echo "Todos los campos deben completarse";
    }
} else {
    echo "Método no permitido.";
}

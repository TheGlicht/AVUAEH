<?php
include_once __DIR__ . '/../../DB/Laboratorio/laboratorioDB.php';

// Obtener datos del registro
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Verificar si existen los campos esperados
    if(isset($_POST['username'], $_POST['email'], $_POST['password'])){
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $pass = $_POST['password'];

        // Validación de los campos no vacios
        if(!empty($username) || !empty($email) || !empty($pass)){
            try{
                // Encriptar la contraseña de forma segura
                $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
                // Crear objeto y guardar en la base de datos
                $laboratorio = new LaboratorioDb();
                $laboratorio->addLab($username, $email, $hashedPass);
                echo "Registro exitoso.";
            }catch(Exception $e){
                echo "Error". $e->getMessage();
            }
        } else{
            echo "Todos los campos son obligatorios";
        }
    } else{
        echo "Faltan datos en el formulario";
    }
} else{
    echo "Método no permitido";
}
?>
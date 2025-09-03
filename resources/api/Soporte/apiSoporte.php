<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../resources/DB/conexion.php';

// Verificar mensaje recibido
$mensaje = $_POST['message'] ?? '';
if (empty($mensaje)) {
    die("ERROR: Faltan datos");
}

// Verificar sesión de usuario
if (!isset($_SESSION['username'])) {
    die("ERROR: Usuario no autenticado");
}
$username = $_SESSION['username'];

// Obtener conexión PDO desde la clase Conexion
$conexion = Conexion::getInstancia();
$pdo = $conexion->getDbh();

// Buscar email en las 3 tablas
$email = null;
$tablas = ['Alumno', 'Docentes', 'Laboratorio'];

foreach ($tablas as $tabla) {
    $stmt = $pdo->prepare("SELECT email FROM $tabla WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['email'])) {
        $email = $row['email'];
        break;
    }
}

if (!$email) {
    die("ERROR: No se encontró el correo del usuario");
}

// Enviar correo con PHPMailer
try {
    $mail = new PHPMailer(true);

    // Configuración SMTP de Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'innovatercodecompany@gmail.com';
    $mail->Password = 'm t d u o g a j d i w d d f p s'; // Contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remitente (correo del usuario)
    $mail->setFrom($email, 'Soporte Usuario');

    // Destinatario (tu correo de soporte)
    $mail->addAddress('innovatercodecompany@gmail.com', 'Soporte InnovaterCode');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = "Ticket de soporte - " . $email;
    $mail->Body = "<b>Correo del usuario:</b> {$email}<br><br><b>Mensaje:</b><br>" . nl2br(htmlspecialchars($mensaje));

    $mail->send();
    echo "OK";

} catch (Exception $e) {
    echo "ERROR: " . $mail->ErrorInfo;
}

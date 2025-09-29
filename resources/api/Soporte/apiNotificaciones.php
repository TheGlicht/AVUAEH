<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../resources/DB/conexion.php';

// Datos recibidos (cuando se cree un nuevo evento)
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$username = $_POST['username'] ?? '';

if (empty($titulo) || empty($fecha) || empty($username)) {
    die("ERROR: Faltan datos");
}

// Conexión a la BD
$conexion = Conexion::getInstancia();
$pdo = $conexion->getDbh();

// Buscar el email del usuario en las tablas
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

// Enviar correo de notificación
try {
    $mail = new PHPMailer(true);

    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'innovatercodecompany@gmail.com'; // tu correo institucional
    $mail->Password = 'm t d u o g a j d i w d d f p s'; // contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remitente (tu aplicación)
    $mail->setFrom('innovatercodecompany@gmail.com', 'Notificaciones InnovaterCode');

    // Destinatario (el usuario)
    $mail->addAddress($email);

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = "Nuevo evento creado: {$titulo}";
    $mail->Body = "
        <h2>Se ha creado un nuevo evento</h2>
        <p><b>Título:</b> {$titulo}</p>
        <p><b>Descripción:</b> {$descripcion}</p>
        <p><b>Fecha:</b> {$fecha}</p>
    ";

    $mail->send();
    echo "OK";

} catch (Exception $e) {
    echo "ERROR: " . $mail->ErrorInfo;
}

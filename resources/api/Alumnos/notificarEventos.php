<?php
require_once __DIR__ . '/../DB/Alumno/eventosDB.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function notificarCambioEvento($username, $accion, $datosEvento) {
    $eventoDb = new EventoDb();

    try {
        // 1. Buscar email del usuario
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();

        $stmt = $dbh->prepare("SELECT email FROM Alumno WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$alumno || empty($alumno['email'])) {
            return; // No hay correo, no se notifica
        }

        $emailAlumno = $alumno['email'];

        // 2. Preparar cuerpo del correo
        $cuerpo = "<h2>Se ha realizado un cambio en tu calendario</h2>";
        $cuerpo .= "<b>Acción:</b> " . htmlspecialchars($accion) . "<br>";
        $cuerpo .= "<b>Título:</b> " . htmlspecialchars($datosEvento['tituloEvento']) . "<br>";
        $cuerpo .= "<b>Fecha:</b> " . htmlspecialchars($datosEvento['fechaEvento']) . "<br>";
        $cuerpo .= "<b>Descripción:</b> " . htmlspecialchars($datosEvento['descripcion'] ?? '') . "<br>";

        // 3. Configuración PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'innovatercodecompany@gmail.com';
        $mail->Password = 'm t d u o g a j d i w d d f p s';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('innovatercodecompany@gmail.com', 'Agenda Virtual');
        $mail->addAddress($emailAlumno, $username);
        // También puedes poner copia oculta a soporte:
        $mail->addBCC('innovatercodecompany@gmail.com', 'Soporte');

        $mail->isHTML(true);
        $mail->Subject = "Notificación de calendario - {$accion}";
        $mail->Body = $cuerpo;

        $mail->send();

    } catch (Exception $e) {
        error_log("Error enviando notificación: " . $e->getMessage());
    }
}

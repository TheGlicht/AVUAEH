<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../resources/DB/conexion.php';

$id_materia = $id_materia ?? ($_POST['materiaKit'] ?? '');
$id_docente = $id_docente ?? ($_POST['docenteLab'] ?? '');
$id_lab     = $id_lab ?? ($_POST['laboratorio'] ?? '');
$fecha      = $fecha ?? ($_POST['fecha'] ?? '');
$hora       = $hora ?? ($_POST['hora'] ?? '');
$grupo      = $grupo ?? ($_POST['grupo'] ?? '');
$semestre   = $semestre ?? ($_POST['semestre'] ?? '');

if (empty($id_materia) || empty($fecha) || empty($hora) || empty($grupo) || empty($semestre)) {
    die("ERROR: Faltan datos");
}

$conexion = Conexion::getInstancia();
$pdo = $conexion->getDbh();

//  Buscar correos de los alumnos
$sql = "SELECT a.email 
        FROM DatosA d
        INNER JOIN Alumno a ON d.id_alumno = a.id_alumno
        WHERE d.semestre = :semestre AND d.grupo = :grupo";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':semestre' => $semestre,
    ':grupo' => $grupo
]);
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$alumnos) {
    die("ERROR: No se encontraron alumnos para notificar");
}

//  Obtener nombre de la materia
$sqlMateria = "SELECT nombre FROM Materias WHERE id_materias = :id";
$stmtMateria = $pdo->prepare($sqlMateria);
$stmtMateria->execute([':id' => $id_materia]);
$materiaData = $stmtMateria->fetch(PDO::FETCH_ASSOC);
$nombreMateria = $materiaData ? $materiaData['nombre'] : "Desconocida";

//  Mapear nombre de laboratorio
$labNombres = [
    1 => "Lab. Electrónica",
    2 => "Lab. Control",
    3 => "Lab. Fisico-Química",
    4 => "Laboratorio 1",
    5 => "Laboratorio 2",
    6 => "Laboratorio 3",
    7 => "Laboratorio 4"
];
$nombreLab = $labNombres[$id_lab] ?? "Laboratorio desconocido";

try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'innovatercodecompany@gmail.com';
    $mail->Password = 'm t d u o g a j d i w d d f p s'; // app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('innovatercodecompany@gmail.com', 'Notificaciones InnovaterCode');

    foreach ($alumnos as $alumno) {
        if (!empty($alumno['email'])) {
            $mail->addBCC($alumno['email']);
        }
    }

    $mail->isHTML(true);
    $mail->Subject = "Nueva práctica de laboratorio";
    $mail->Body = "
        <h2>Se ha programado una nueva práctica</h2>
        <p><b>Materia:</b> {$nombreMateria}</p>
        <p><b>Semestre:</b> {$semestre}</p>
        <p><b>Grupo:</b> {$grupo}</p>
        <p><b>Laboratorio:</b> {$nombreLab}</p>
        <p><b>Fecha:</b> {$fecha}</p>
        <p><b>Hora:</b> {$hora}</p>
    ";

    $mail->send();
    echo "OK";
} catch (Exception $e) {
    echo "ERROR: " . $mail->ErrorInfo;
}

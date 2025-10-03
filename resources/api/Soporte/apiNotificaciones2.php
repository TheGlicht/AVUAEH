<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../resources/DB/conexion.php';

//  Recibir datos del evento desde POST
$titulo      = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha       = $_POST['fecha'] ?? '';
$id_materia  = $_POST['materia'] ?? '';
$semestre    = $_POST['semestre'] ?? '';
$grupo       = $_POST['grupo'] ?? '';
$id_docente  = $_POST['docente'] ?? ''; 

if (empty($titulo) || empty($fecha) || empty($id_materia) || empty($semestre) || empty($grupo) || empty($id_docente)) {
    die("ERROR: Faltan datos");
}

$conexion = Conexion::getInstancia();
$pdo = $conexion->getDbh();

//  Buscar correos de los alumnos de ese semestre y grupo
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

//  Obtener nombre completo del docente
$sqlDocente = "SELECT nombreCompleto FROM Docentes WHERE id_docente = :id";
$stmtDocente = $pdo->prepare($sqlDocente);
$stmtDocente->execute([':id' => $id_docente]);
$docenteData = $stmtDocente->fetch(PDO::FETCH_ASSOC);
$nombreDocente = $docenteData ? $docenteData['nombreCompleto'] : "Desconocido";

try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'innovatercodecompany@gmail.com';
    $mail->Password = 'm t d u o g a j d i w d d f p s'; // tu app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('innovatercodecompany@gmail.com', 'Notificaciones InnovaterCode');

    //  Añadir alumnos como BCC
    foreach ($alumnos as $alumno) {
        if (!empty($alumno['email'])) {
            $mail->addBCC($alumno['email']);
        }
    }

    $mail->isHTML(true);
    $mail->Subject = "Nuevo evento programado";
    $mail->Body = "
    <h2>Se ha registrado un nuevo evento</h2>
    <p><b>Título:</b> {$titulo}</p>
    <p><b>Descripción:</b> {$descripcion}</p>
    <p><b>Materia:</b> {$nombreMateria}</p>
    <p><b>Semestre:</b> {$semestre}</p>
    <p><b>Grupo:</b> {$grupo}</p>
    <p><b>Docente:</b> {$nombreDocente}</p>
    <p><b>Fecha:</b> {$fecha}</p>
";


    $mail->send();
    echo "OK";
} catch (Exception $e) {
    echo "ERROR: " . $mail->ErrorInfo;
}

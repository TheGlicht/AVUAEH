<?php
session_start();
require_once __DIR__ . '/../../DB/Alumno/contactosDB.php';

// Verificar autenticación
if (!isset($_SESSION['username'])) {
    die("Acceso no autorizado");
}

$contactoDb = new ContactoDb();
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

// Debug opcional: 
// file_put_contents("debug_contactos.log", "Accion: $action\n", FILE_APPEND);

try {
    switch ($action) {
        case 'listar':
            $contactos = $contactoDb->getContactosByAlumno($_SESSION['username']);

            if (empty($contactos)) {
                echo "<tr><td colspan='4' class='text-center text-muted'>No tienes contactos guardados.</td></tr>";
                break;
            }

            foreach ($contactos as $c) {
                $id  = (int)$c['id_contacto'];
                $nom = htmlspecialchars($c['nombreCompleto'], ENT_QUOTES, 'UTF-8');
                $tel = htmlspecialchars($c['telefono'], ENT_QUOTES, 'UTF-8');
                $cor = htmlspecialchars($c['email'], ENT_QUOTES, 'UTF-8');

                echo "<tr>
                        <td>{$nom}</td>
                        <td>{$tel}</td>
                        <td>{$cor}</td>
                        <td>
                          <button class='btn btn-warning btn-sm me-1 edit-btn'
                                  title='Editar'
                                  data-id='{$id}'
                                  data-nombre='{$nom}'
                                  data-telefono='{$tel}'
                                  data-correo='{$cor}'>
                            <i class='fa-solid fa-pen-to-square'></i>
                          </button>

                          <button class='btn btn-danger btn-sm delete-btn'
                                  title='Eliminar'
                                  data-id='{$id}'>
                            <i class='fa-solid fa-trash'></i>
                          </button>
                        </td>
                      </tr>";
            }
            break;

        case 'agregar':
            $nombre   = $_POST['nombre']   ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $correo   = $_POST['correo']   ?? '';

            if (!$nombre || !$telefono || !$correo) {
                echo "ERROR: Datos incompletos";
                break;
            }

            if ($contactoDb->addContacto($nombre, $telefono, $correo, $_SESSION['username'])) {
                echo "OK";
            } else {
                echo "ERROR";
            }
            break;

        case 'editar':
            $id       = $_POST['id_contacto'] ?? '';
            $nombre   = $_POST['nombre']      ?? '';
            $telefono = $_POST['telefono']    ?? '';
            $correo   = $_POST['correo']      ?? '';

            if (!$id || !$nombre || !$telefono || !$correo) {
                echo "ERROR: Datos incompletos";
                break;
            }

            if ($contactoDb->updateContacto($id, $nombre, $telefono, $correo, $_SESSION['username'])) {
                echo "OK";
            } else {
                echo "ERROR";
            }
            break;

        case 'eliminar':
            $id = $_POST['id_contacto'] ?? '';
            if (!$id) {
                echo "ERROR: Falta id_contacto";
                break;
            }

            if ($contactoDb->deleteContacto($id, $_SESSION['username'])) {
                echo "OK";
            } else {
                echo "ERROR: No se pudo eliminar el contacto";
            }
            break;
        
        case 'sugerencias':
            require_once __DIR__ . '/../../DB/Alumno/alumnosDB.php';
            $dataAlumnoDb = new DataAlumnoDb();
            $sugerencias = $dataAlumnoDb->getSugerencias($_SESSION['username']);

            if (empty($sugerencias)) {
                echo "<li class='list-group-item text-muted'>No hay sugerencias por ahora.</li>";
                break;
            }

            foreach ($sugerencias as $s) {
                $nom = htmlspecialchars($s['nombreCompleto'], ENT_QUOTES, 'UTF-8');
                $usr = htmlspecialchars($s['username'], ENT_QUOTES, 'UTF-8');
                $sem = htmlspecialchars($s['semestre'], ENT_QUOTES, 'UTF-8');
                $gru = htmlspecialchars($s['grupo'], ENT_QUOTES, 'UTF-8');
                $tel = htmlspecialchars($s['telefono'], ENT_QUOTES, 'UTF-8');
                $cor = htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8');
        
                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                        <div>
                            <strong>{$nom}</strong> <small class='text-muted'>@$usr</small><br>
                            <span class='badge bg-primary'>Sem {$sem}</span>
                            <span class='badge bg-secondary'>Grupo {$gru}</span>
                        </div>
                        <button class='btn btn-sm btn-success add-suggest-btn'
                                data-username='{$usr}'
                                data-nombre='{$nom}'
                                data-telefono='{$tel}'
                                data-correo='{$cor}'>
                            <i class='fa fa-user-plus'></i> Agregar
                        </button>
                    </li>";
            }
            break;

        default:
            echo "Acción no válida";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

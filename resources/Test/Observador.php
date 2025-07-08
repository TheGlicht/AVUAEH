<?php
// Incluir el archivo de conexión
include_once '../../resources/DB/conexion.php';

// Obtener la instancia de la conexión
$conn = Conexion::getInstancia()->getDbh();

// Obtener la tabla seleccionada (si existe)
$tablaSeleccionada = $_GET['tabla'] ?? null;
$datosTabla = [];
$columnasTabla = [];

if ($tablaSeleccionada) {
    try {
        // Obtener estructura de la tabla
        $queryColumnas = $conn->query("DESCRIBE $tablaSeleccionada");
        $columnasTabla = $queryColumnas->fetchAll(PDO::FETCH_COLUMN);
        
        // Obtener datos de la tabla (limitado a 50 registros para no sobrecargar)
        $queryDatos = $conn->query("SELECT * FROM $tablaSeleccionada LIMIT 50");
        $datosTabla = $queryDatos->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        $error = "Error al obtener datos de la tabla: " . $e->getMessage();
    }
}

// Obtener listado de todas las tablas
try {
    $query = $conn->query("SHOW TABLES");
    $tables = $query->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $error = "Error al obtener las tablas: " . $e->getMessage();
    $tables = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBSERVADOR.EXE</title>
    <link rel="stylesheet" href="../../public/components/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../public/components/css/styleIndex.css" />
    <style>
        .tabla-container {
            margin-top: 20px;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .nav-tabs .nav-link.active {
            font-weight: bold;
            border-bottom: 3px solid #0d6efd;
        }
        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }
        .tabla-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .sql-query {
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <h2 class="text-center mb-4">Observador de Base de Datos</h2>
        
        <!-- Sección de alertas -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Panel de tablas disponibles -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tablas Disponibles</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($tables as $table): ?>
                            <a href="?tabla=<?php echo urlencode($table); ?>" 
                               class="list-group-item list-group-item-action <?php echo ($table === $tablaSeleccionada) ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($table); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Panel de contenido -->
            <div class="col-md-9">
                <?php if ($tablaSeleccionada): ?>
                    <!-- Información de la tabla seleccionada -->
                    <div class="tabla-info">
                        <h4>Tabla: <strong><?php echo htmlspecialchars($tablaSeleccionada); ?></strong></h4>
                        <div class="sql-query mb-2">SELECT * FROM <?php echo htmlspecialchars($tablaSeleccionada); ?> LIMIT 50;</div>
                        <p>Mostrando <?php echo count($datosTabla); ?> registros</p>
                    </div>
                    
                    <!-- Pestañas para estructura y datos -->
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#estructura">Estructura</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#datos">Datos</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <!-- Pestaña de estructura -->
                        <div class="tab-pane fade show active" id="estructura">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Columna</th>
                                            <th>Tipo</th>
                                            <th>Nulo</th>
                                            <th>Clave</th>
                                            <th>Predeterminado</th>
                                            <th>Extra</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $query = $conn->query("SHOW FULL COLUMNS FROM $tablaSeleccionada");
                                            $columnas = $query->fetchAll(PDO::FETCH_ASSOC);
                                        ?>
                                        <?php foreach ($columnas as $columna): ?>
                                            <tr>
                                                <td><strong><?php echo $columna['Field']; ?></strong></td>
                                                <td><?php echo $columna['Type']; ?></td>
                                                <td><?php echo $columna['Null']; ?></td>
                                                <td><?php echo $columna['Key']; ?></td>
                                                <td><?php echo $columna['Default'] ?? 'NULL'; ?></td>
                                                <td><?php echo $columna['Extra']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Pestaña de datos -->
                        <div class="tab-pane fade" id="datos">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <?php foreach ($columnasTabla as $columna): ?>
                                                <th><?php echo htmlspecialchars($columna); ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($datosTabla as $fila): ?>
                                            <tr>
                                                <?php foreach ($columnasTabla as $columna): ?>
                                                    <td>
                                                        <?php 
                                                            if (is_null($fila[$columna])) {
                                                                echo '<span class="text-muted">NULL</span>';
                                                            } else {
                                                                echo htmlspecialchars($fila[$columna]);
                                                            }
                                                        ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <div class="alert alert-info">
                        Selecciona una tabla del panel izquierdo para ver su estructura y datos.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../public/components/js/jquery-3.7.1.js"></script>
    <script src="../../public/components/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/components/js/KitFontAwesome.js"></script>
    <script src="../../public/components/js/Alu/funcionesModulares.js"></script>
</body>
</html>

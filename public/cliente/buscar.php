<?php
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/controllers/AuthController.php';
require_once __DIR__ . '/../../src/controllers/FlightController.php';

$database = new Database();
$db = $database->getConnection();
$authController = new AuthController($db);
$authController->requireRole('cliente');
$flightController = new FlightController($db);

$flights = $flightController->getAllFlights();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Vuelos disponibles</title>
</head>
<body>
    <header>
        <h1>Vuelos disponibles</h1>
        <nav>
            <a href="../logout.php">Cerrar sesión</a>
        </nav>
    </header>
    <main class="container">
        <?php if (empty($flights)): ?>
            <div class="error">No hay vuelos disponibles en este momento.</div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Código del vuelo</th>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Ciudad de origen</th>
                        <th>Ciudad de destino</th>
                        <th>Fecha del vuelo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($flights as $flight): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($flight['flight_code']); ?></td>
                            <td><?php echo htmlspecialchars($flight['cedula']); ?></td>
                            <td><?php echo htmlspecialchars($flight['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($flight['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($flight['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($flight['ciudad_origen']); ?></td>
                            <td><?php echo htmlspecialchars($flight['ciudad_destino']); ?></td>
                            <td><?php echo htmlspecialchars($flight['fecha_vuelo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>

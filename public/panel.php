<?php
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/FlightController.php';

$database = new Database();
$db = $database->getConnection();
$authController = new AuthController($db);
$authController->requireRole('admin');

$flightController = new FlightController($db);
$flights = $flightController->getAllFlights();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Sistema de Reserva de Vuelos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Panel de Administración</h1>
        <nav>
            <a href="administrador/vuelos.php">Vuelos</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </header>
    <main class="container">
        <section class="admin-actions">
            <div class="action-card">
                <h2>Administrar Vuelos</h2>
                <a class="button-link" href="administrador/vuelos.php">Registrar vuelo</a>
                <a class="button-link" href="administrador/vuelos.php">Consultar vuelos</a>
                <a class="button-link" href="administrador/vuelos.php">Modificar / Eliminar vuelo</a>
            </div>
        </section>
        <section>
            <h2>Resumen de Vuelos</h2>
            <p>En este panel puede administrar vuelos.</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Código del Vuelo</th>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Fecha</th>
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
        </section>
    </main>
</body>
</html>
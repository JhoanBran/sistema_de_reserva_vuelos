<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/FlightController.php';

$flightController = new FlightController($db);
$flights = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $origin = $_POST['origin'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $date = $_POST['date'] ?? '';

    $flights = $flightController->searchFlights($origin, $destination, $date);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <title>Buscar Vuelos</title>
</head>
<body>
    <div class="container">
        <h1>Buscar Vuelos</h1>
        <form method="POST" action="search.php">
            <label for="origin">Ciudad de Origen:</label>
            <input type="text" id="origin" name="origin" required>

            <label for="destination">Ciudad de Destino:</label>
            <input type="text" id="destination" name="destination" required>

            <label for="date">Fecha del Vuelo:</label>
            <input type="date" id="date" name="date" required>

            <button type="submit">Buscar</button>
        </form>

        <?php if (!empty($flights)): ?>
            <h2>Vuelos Disponibles</h2>
            <table>
                <thead>
                    <tr>
                        <th>Código del Vuelo</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Ciudad de Origen</th>
                        <th>Ciudad de Destino</th>
                        <th>Fecha del Vuelo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($flights as $flight): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($flight['codigo_vuelo']); ?></td>
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
    </div>
</body>
</html>
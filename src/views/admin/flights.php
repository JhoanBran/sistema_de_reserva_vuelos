<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/FlightController.php';

$flightController = new FlightController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_flight'])) {
        $flightController->addFlight($_POST);
    } elseif (isset($_POST['edit_flight'])) {
        $flightController->editFlight($_POST);
    } elseif (isset($_POST['delete_flight'])) {
        $flightController->deleteFlight($_POST['flight_id']);
    }
}

$flights = $flightController->getAllFlights();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <title>Gestión de Vuelos</title>
</head>
<body>
    <h1>Gestión de Vuelos</h1>
    <form method="POST" action="">
        <input type="text" name="flight_code" placeholder="Código del vuelo" required>
        <input type="text" name="cedula" placeholder="Cédula" required>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="text" name="telefono" placeholder="Teléfono de contacto" required>
        <input type="text" name="ciudad_origen" placeholder="Ciudad de origen" required>
        <input type="text" name="ciudad_destino" placeholder="Ciudad de destino" required>
        <input type="date" name="fecha_vuelo" required>
        <button type="submit" name="add_flight">Agregar Vuelo</button>
    </form>

    <h2>Lista de Vuelos</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Ciudad Origen</th>
                <th>Ciudad Destino</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($flights as $flight): ?>
                <tr>
                    <td><?php echo $flight['flight_code']; ?></td>
                    <td><?php echo $flight['cedula']; ?></td>
                    <td><?php echo $flight['nombre']; ?></td>
                    <td><?php echo $flight['apellido']; ?></td>
                    <td><?php echo $flight['telefono']; ?></td>
                    <td><?php echo $flight['ciudad_origen']; ?></td>
                    <td><?php echo $flight['ciudad_destino']; ?></td>
                    <td><?php echo $flight['fecha_vuelo']; ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="flight_id" value="<?php echo $flight['id']; ?>">
                            <button type="submit" name="edit_flight">Editar</button>
                            <button type="submit" name="delete_flight">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
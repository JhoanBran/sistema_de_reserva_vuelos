<?php
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/controllers/AuthController.php';
require_once __DIR__ . '/../../src/controllers/FlightController.php';

$database = new Database();
$db = $database->getConnection();
$authController = new AuthController($db);
$authController->requireRole('admin');
$flightController = new FlightController($db);

$message = '';
$error = '';
$editingFlight = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_flight'])) {
        $data = [
            'flight_code' => trim($_POST['flight_code'] ?? ''),
            'cedula' => trim($_POST['cedula'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'ciudad_origen' => trim($_POST['ciudad_origen'] ?? ''),
            'ciudad_destino' => trim($_POST['ciudad_destino'] ?? ''),
            'fecha_vuelo' => trim($_POST['fecha_vuelo'] ?? ''),
        ];

        if ($flightController->flightExists($data['flight_code'])) {
            $error = 'El código de vuelo ya existe. Usa otro código diferente.';
        } elseif ($flightController->addFlight($data)) {
            $message = 'Vuelo agregado correctamente.';
        } else {
            $error = 'No se pudo agregar el vuelo. Verifica los datos e inténtalo de nuevo.';
        }
    }

    if (isset($_POST['update_flight'])) {
        $flightCode = trim($_POST['flight_code'] ?? '');
        $data = [
            'cedula' => trim($_POST['cedula'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'ciudad_origen' => trim($_POST['ciudad_origen'] ?? ''),
            'ciudad_destino' => trim($_POST['ciudad_destino'] ?? ''),
            'fecha_vuelo' => trim($_POST['fecha_vuelo'] ?? ''),
        ];

        if ($flightController->updateFlight($flightCode, $data)) {
            $message = 'Vuelo actualizado correctamente.';
        } else {
            $error = 'No se pudo actualizar el vuelo.';
        }
    }

    if (isset($_POST['delete_flight'])) {
        $flightCode = trim($_POST['flight_code'] ?? '');
        if ($flightController->deleteFlight($flightCode)) {
            $message = 'Vuelo eliminado correctamente.';
        } else {
            $error = 'No se pudo eliminar el vuelo.';
        }
    }
}

if (isset($_GET['edit'])) {
    $flightCode = trim($_GET['edit']);
    $editingFlight = $flightController->getFlight($flightCode);
}

$flights = $flightController->getAllFlights();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Gestión de Vuelos</title>
</head>
<body>
    <header>
        <h1>Gestión de Vuelos</h1>
        <nav>
            <a href="../panel.php">Inicio</a>
            <a href="../logout.php">Cerrar sesión</a>
        </nav>
    </header>
    <main class="container">
        <?php if (!empty($message)): ?><div class="success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if (!empty($error)): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

        <section>
            <h2><?php echo $editingFlight ? 'Editar Vuelo' : 'Agregar Vuelo'; ?></h2>
            <form method="POST" action="">
                <label for="flight_code">Código del vuelo</label>
                <input type="text" id="flight_code" name="flight_code" value="<?php echo $editingFlight ? htmlspecialchars($editingFlight['flight_code']) : ''; ?>" <?php echo $editingFlight ? 'readonly' : 'required'; ?>>

                <label for="cedula">Cédula</label>
                <input type="text" id="cedula" name="cedula" value="<?php echo $editingFlight ? htmlspecialchars($editingFlight['cedula']) : ''; ?>" required>

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $editingFlight ? htmlspecialchars($editingFlight['nombre']) : ''; ?>" required>

                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo $editingFlight ? htmlspecialchars($editingFlight['apellido']) : ''; ?>" required>

                <label for="telefono">Teléfono de contacto</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo $editingFlight ? htmlspecialchars($editingFlight['telefono']) : ''; ?>" required>

                <label for="ciudad_origen">Ciudad de origen</label>
                <input type="text" id="ciudad_origen" name="ciudad_origen" value="<?php echo $editingFlight ? htmlspecialchars($editingFlight['ciudad_origen']) : ''; ?>" required>

                <label for="ciudad_destino">Ciudad de destino</label>
                <input type="text" id="ciudad_destino" name="ciudad_destino" value="<?php echo $editingFlight ? htmlspecialchars($editingFlight['ciudad_destino']) : ''; ?>" required>

                <label for="fecha_vuelo">Fecha del vuelo</label>
                <input type="date" id="fecha_vuelo" name="fecha_vuelo" value="<?php echo $editingFlight ? htmlspecialchars($editingFlight['fecha_vuelo']) : ''; ?>" required>

                <?php if ($editingFlight): ?>
                    <button type="submit" name="update_flight">Actualizar Vuelo</button>
                    <a href="vuelos.php">Cancelar</a>
                <?php else: ?>
                    <button type="submit" name="add_flight">Agregar Vuelo</button>
                <?php endif; ?>
            </form>
        </section>
        <section>
            <h2>Lista de Vuelos</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
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
                            <td>
                                <a href="vuelos.php?edit=<?php echo urlencode($flight['flight_code']); ?>">Editar</a>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="flight_code" value="<?php echo htmlspecialchars($flight['flight_code']); ?>">
                                    <button type="submit" name="delete_flight" onclick="return confirm('¿Eliminar este vuelo?');">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>

<?php
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Flight.php';

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);
$flightModel = new Flight($db);

$defaultUsers = [
    [
        'username' => 'admin',
        'password' => 'admin123',
        'cedula' => '0000000000',
        'nombre' => 'Admin',
        'apellido' => 'Sistema',
        'telefono' => '0000000000',
        'tipo_usuario' => 'admin',
    ],
    [
        'username' => 'cliente',
        'password' => 'cliente123',
        'cedula' => '1111111111',
        'nombre' => 'Usuario',
        'apellido' => 'Cliente',
        'telefono' => '1111111111',
        'tipo_usuario' => 'cliente',
    ],
];

$defaultFlights = [
    [
        'flight_code' => 'FL100',
        'cedula' => '1111111111',
        'nombre' => 'Usuario',
        'apellido' => 'Cliente',
        'telefono' => '1111111111',
        'ciudad_origen' => 'Madrid',
        'ciudad_destino' => 'París',
        'fecha_vuelo' => '2026-07-01',
    ],
    [
        'flight_code' => 'FL101',
        'cedula' => '0000000000',
        'nombre' => 'Admin',
        'apellido' => 'Sistema',
        'telefono' => '0000000000',
        'ciudad_origen' => 'Bogotá',
        'ciudad_destino' => 'Medellín',
        'fecha_vuelo' => '2026-07-05',
    ],
];

$results = [];

foreach ($defaultUsers as $userData) {
    $existingUser = $userModel->findByUsername($userData['username']);
    if ($existingUser) {
        if (!password_verify($userData['password'], $existingUser['password'])) {
            $securePassword = password_hash($userData['password'], PASSWORD_DEFAULT);
            $updateStmt = $db->prepare("UPDATE users SET password = :password WHERE username = :username");
            $updateStmt->execute([
                ':password' => $securePassword,
                ':username' => $userData['username'],
            ]);
            $results[] = "Contraseña de '{$userData['username']}' actualizada correctamente.";
        } else {
            $results[] = "El usuario '{$userData['username']}' ya existe.";
        }
        continue;
    }

    $created = $userModel->createUser($userData);
    if ($created) {
        $results[] = "Usuario '{$userData['username']}' creado correctamente.";
    } else {
        $results[] = "Error al crear el usuario '{$userData['username']}'.";
    }
}

foreach ($defaultFlights as $flightData) {
    $existingFlight = $flightModel->getByCode($flightData['flight_code']);
    if ($existingFlight) {
        $results[] = "El vuelo '{$flightData['flight_code']}' ya existe.";
        continue;
    }

    if ($flightModel->create($flightData)) {
        $results[] = "Vuelo '{$flightData['flight_code']}' creado correctamente.";
    } else {
        $results[] = "Error al crear el vuelo '{$flightData['flight_code']}'.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuarios Predeterminados</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .result { margin-bottom: 10px; }
        .success { color: green; }
        .error { color: darkred; }
    </style>
</head>
<body>
    <h1>Generar usuarios predeterminados</h1>
    <p>Credenciales creadas o verificadas:</p>
    <ul>
        <li><strong>Admin</strong> — usuario: <code>admin</code>, contraseña: <code>admin123</code></li>
        <li><strong>Cliente</strong> — usuario: <code>cliente</code>, contraseña: <code>cliente123</code></li>
    </ul>
    <?php foreach ($results as $result): ?>
        <div class="result"><?php echo htmlspecialchars($result, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endforeach; ?>
    <p>Después de crear los usuarios, elimina este archivo o protégelo si el sistema queda en producción.</p>
</body>
</html>

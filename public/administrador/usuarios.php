<?php
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/controllers/AuthController.php';
require_once __DIR__ . '/../../src/models/User.php';

$database = new Database();
$db = $database->getConnection();
$authController = new AuthController($db);
$authController->requireRole('admin');
$userModel = new User($db);

$message = '';
$error = '';
$editingUser = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'cedula' => trim($_POST['cedula'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'tipo_usuario' => trim($_POST['tipo_usuario'] ?? 'cliente'),
        ];

        if ($userModel->createUser($data)) {
            $message = 'Usuario creado correctamente.';
        } else {
            $error = 'No se pudo crear el usuario. Verifique los datos e inténtelo de nuevo.';
        }
    }

    if (isset($_POST['update_user'])) {
        $id = (int) ($_POST['id'] ?? 0);
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'cedula' => trim($_POST['cedula'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'tipo_usuario' => trim($_POST['tipo_usuario'] ?? 'cliente'),
        ];

        if ($userModel->updateUser($id, $data)) {
            $message = 'Usuario actualizado correctamente.';
        } else {
            $error = 'No se pudo actualizar el usuario.';
        }
    }

    if (isset($_POST['delete_user'])) {
        $id = (int) ($_POST['id'] ?? 0);
        if ($userModel->deleteUser($id)) {
            $message = 'Usuario eliminado correctamente.';
        } else {
            $error = 'No se pudo eliminar el usuario.';
        }
    }
}

if (isset($_GET['edit'])) {
    $id = (int) ($_GET['edit'] ?? 0);
    $editingUser = $userModel->findById($id);
}

$users = $userModel->getAllUsers();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <header>
        <h1>Gestión de Usuarios</h1>
        <nav>
            <a href="../panel.php">Inicio</a>
            <a href="vuelos.php">Vuelos</a>
            <a href="../logout.php">Cerrar sesión</a>
        </nav>
    </header>
    <main class="container">
        <?php if (!empty($message)): ?><div class="success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if (!empty($error)): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

        <section>
            <h2><?php echo $editingUser ? 'Editar Usuario' : 'Agregar Usuario'; ?></h2>
            <form method="POST" action="">
                <?php if ($editingUser): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editingUser['id']); ?>">
                <?php endif; ?>

                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" value="<?php echo $editingUser ? htmlspecialchars($editingUser['username']) : ''; ?>" required>

                <label for="password">Contraseña<?php echo $editingUser ? ' (dejar en blanco para no cambiar)' : ''; ?></label>
                <input type="password" id="password" name="password" <?php echo $editingUser ? '' : 'required'; ?>>

                <label for="cedula">Cédula</label>
                <input type="text" id="cedula" name="cedula" value="<?php echo $editingUser ? htmlspecialchars($editingUser['cedula']) : ''; ?>" required>

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $editingUser ? htmlspecialchars($editingUser['nombre']) : ''; ?>" required>

                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo $editingUser ? htmlspecialchars($editingUser['apellido']) : ''; ?>" required>

                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo $editingUser ? htmlspecialchars($editingUser['telefono']) : ''; ?>" required>

                <label for="tipo_usuario">Tipo de usuario</label>
                <select id="tipo_usuario" name="tipo_usuario" required>
                    <option value="admin" <?php echo $editingUser && $editingUser['tipo_usuario'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                    <option value="cliente" <?php echo $editingUser && $editingUser['tipo_usuario'] === 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                </select>

                <?php if ($editingUser): ?>
                    <button type="submit" name="update_user">Actualizar Usuario</button>
                    <a href="usuarios.php">Cancelar</a>
                <?php else: ?>
                    <button type="submit" name="add_user">Agregar Usuario</button>
                <?php endif; ?>
            </form>
        </section>

        <section>
            <h2>Lista de Usuarios</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['cedula']); ?></td>
                            <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($user['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($user['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($user['tipo_usuario']); ?></td>
                            <td>
                                <a href="usuarios.php?edit=<?php echo $user['id']; ?>">Editar</a>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <button type="submit" name="delete_user" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</button>
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

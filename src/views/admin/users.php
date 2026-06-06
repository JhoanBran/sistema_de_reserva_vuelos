<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/User.php';

$db = new Database();
$conn = $db->getConnection();
$userModel = new User($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $userModel->createUser($username, $password, $role);
    }

    if (isset($_POST['edit_user'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $role = $_POST['role'];

        $userModel->updateUser($id, $username, $role);
    }

    if (isset($_POST['delete_user'])) {
        $id = $_POST['id'];
        $userModel->deleteUser($id);
    }
}

$users = $userModel->getAllUsers();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <h1>Gestión de Usuarios</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Nombre de usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <select name="role" required>
            <option value="admin">Administrador</option>
            <option value="client">Cliente</option>
        </select>
        <button type="submit" name="add_user">Agregar Usuario</button>
    </form>

    <h2>Lista de Usuarios</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre de Usuario</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="delete_user">Eliminar</button>
                </form>
                <button onclick="editUser(<?php echo $user['id']; ?>, '<?php echo $user['username']; ?>', '<?php echo $user['role']; ?>')">Editar</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script src="../../public/js/main.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios, Categorías y Productos</title>
</head>
<body>
    <h1>Gestión de Usuarios</h1>

    <!-- Mensajes de éxito o error -->
    <?php 
    /* 
     * Muestra un mensaje de éxito si existe en la sesión
     * Luego lo elimina de la sesión 
     */
    if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php 
    /* 
     * Muestra un mensaje de error si existe en la sesión
     * Luego lo elimina de la sesión 
     */
    if (isset($_SESSION['error_message'])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Formulario para crear un nuevo usuario (solo visible para administradores) -->
    <?php 
    /* 
     * Comprueba si el usuario actual tiene rol de administrador.
     * Si es administrador, muestra un formulario para crear un nuevo usuario.
     * Si no, muestra un mensaje de acceso denegado.
     */
    if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <h2>Crear Nuevo Usuario</h2>
        <form action="<?= BASE_URL ?>admin/usuarios" method="POST">
            <!-- Campo para el nombre del usuario -->
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="data[nombre]" required>

            <!-- Campo para los apellidos del usuario -->
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="data[apellidos]" required>

            <!-- Campo para el correo electrónico -->
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="data[email]" required>

            <!-- Campo para la contraseña -->
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="data[password]" required>

            <!-- Selección del rol del usuario -->
            <label for="role">Rol:</label>
            <select id="role" name="data[role]">
                <option value="user">Usuario</option>
                <option value="admin">Administrador</option>
            </select>

            <!-- Botón para enviar el formulario -->
            <button type="submit">Crear Usuario</button>
        </form>
    <?php else: ?>
        <!-- Mensaje para usuarios no autorizados -->
        <p style="color: red;">No tienes permisos para crear usuarios.</p>
    <?php endif; ?>

    <!-- Tabla de usuarios -->
    <h2>Lista de Usuarios</h2>
    <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f4f4f4;">
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            /* 
             * Comprueba si hay usuarios disponibles.
             * Si los hay, muestra una fila por cada usuario.
             * Si no, muestra un mensaje indicando que no hay usuarios.
             */
            if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $index => $usuario): ?>
                    <tr style="background-color: <?= $index % 2 === 0 ? '#ffffff' : '#f9f9f9'; ?>;">
                        <!-- ID del usuario -->
                        <td><?= htmlspecialchars($usuario->getId()) ?></td>
                        <!-- Nombre del usuario -->
                        <td><?= htmlspecialchars($usuario->getNombre()) ?></td>
                        <!-- Apellidos del usuario -->
                        <td><?= htmlspecialchars($usuario->getApellidos()) ?></td>
                        <!-- Correo electrónico del usuario -->
                        <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
                        <!-- Rol del usuario -->
                        <td><?= htmlspecialchars($usuario->getRol()) ?></td>
                        <!-- Acción para eliminar al usuario -->
                        <td>
                            <form action="<?= BASE_URL ?>admin/eliminarUsuario/<?= htmlspecialchars($usuario->getId()) ?>" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                <button type="submit" style="background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Mensaje cuando no hay usuarios registrados -->
                <tr>
                    <td colspan="6" style="text-align: center; font-style: italic;">No hay usuarios registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Gestión de Categorías -->
    <h1>Gestión de Categorías</h1>
    <ul>
        <!-- Enlace para listar categorías -->
        <li><a href="<?= BASE_URL ?>categoria/listar">Listar Categorías</a></li>
        <!-- Enlace para crear una nueva categoría -->
        <li><a href="<?= BASE_URL ?>categoria/crear">Crear Categoría</a></li>
    </ul>

    <!-- Gestión de Productos -->
    <h1>Gestión de Productos</h1>
    <ul>
        <!-- Enlace para listar productos -->
        <li><a href="<?= BASE_URL ?>products/list">Listar Productos</a></li>
        <!-- Enlace para crear un nuevo producto -->
        <li><a href="<?= BASE_URL ?>products/create">Crear Producto</a></li>
    </ul>
</body>
</html>

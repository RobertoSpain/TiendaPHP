<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Tienda</title>
    <link rel="stylesheet" href="/miTienda/public/css/style.css">
</head>
<body>
    <header>
        <h1>Bienvenido a mi Tienda</h1>
        <nav>
            <ul>
                <!-- Opciones visibles para todos -->
                <li><a href="<?= BASE_URL ?>">Inicio</a></li>
                <li><a href="<?= BASE_URL ?>products/list">Lista de Productos</a></li>
                <li><a href="<?= BASE_URL ?>categoria/listar">Lista de Categorías</a></li>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Opciones para usuarios registrados -->
                    <li>Bienvenido, <strong><?= htmlspecialchars($_SESSION['user']['nombre']) ?></strong></li>
                    <li><a href="<?= BASE_URL ?>logout">Cerrar Sesión</a></li>

                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <!-- Opciones adicionales para administradores -->
                        <li><a href="<?= BASE_URL ?>products/create">Crear Producto</a></li>
                        <li><a href="<?= BASE_URL ?>categoria/crear">Crear Categoría</a></li>
                        <li><a href="<?= BASE_URL ?>usuario/registrar">Crear Usuario</a></li>
                        <li><a href="<?= BASE_URL ?>admin/usuarios">Lista de Usuarios</a></li>

                    <?php endif; ?>

                <?php else: ?>
                    <!-- Opciones para usuarios no registrados -->
                    <li><a href="<?= BASE_URL ?>login">Iniciar Sesión</a></li>
                    <li><a href="<?= BASE_URL ?>register">Registrarse</a></li>
                <?php endif; ?>

                <!-- Enlace al carrito visible para todos -->
                <li><a href="<?= BASE_URL ?>carrito/view" class="button">Ver Carrito</a></li>
            </ul>
        </nav>
    </header>
    <hr>


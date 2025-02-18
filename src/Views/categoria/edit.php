<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
</head>
<body>
    <!-- Enlace al inicio -->
    <p><a href="<?= BASE_URL ?>">Inicio</a></p>
    
    <h1>Editar Categoría</h1>

    <!-- Mensajes de éxito o error -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['success_message']) ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['error_message']) ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Formulario para editar la categoría -->
    <!-- Notar que el action se ajusta para que la ruta coincida con la definida en Routes.php: /categoria/actualizar/(\d+) -->
    <form action="<?= BASE_URL ?>categoria/actualizar/<?= htmlspecialchars($category->getId()) ?>" method="POST">
        <label for="nombre">Nombre de la categoría:</label>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($category->getNombre()) ?>" required>
        <button type="submit">Actualizar Categoría</button>
    </form>
    
    <a href="<?= BASE_URL ?>categoria/listar">Volver al listado de categorías</a>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>

    <echo>Hola desde edit.php</echo>

    <!-- Mensajes de éxito/error si los usas -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>products/update" method="POST" enctype="multipart/form-data">
        <!-- Campo oculto con el ID del producto -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($product->getId()) ?>">

        <!-- Campo para el nombre -->
        <label for="name">Nombre producto:</label>
        <input type="text" id="name" name="name" 
               value="<?= htmlspecialchars($product->getName()) ?>" required>

        <!-- Campo para la descripción -->
        <label for="description">Descripción:</label>
        <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($product->getDescription()) ?></textarea>

        <!-- Campo para el precio -->
        <label for="price">Precio:</label>
        <input type="number" id="price" name="price" step="0.01"
               value="<?= htmlspecialchars($product->getPrice()) ?>" required>

        <!-- Campo para el stock -->
        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" min="0"
               value="<?= htmlspecialchars($product->getStock()) ?>" required>

        <!-- Select de categorías (si pasaste $categories) -->
        <label for="category_id">Categoría:</label>
        <select id="category_id" name="category_id" required>
            <option value="">-- Selecciona la categoría --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['id']) ?>"
                    <?php if ($product->getCategoryId() == $cat['id']): ?>selected<?php endif; ?>>
                    <?= htmlspecialchars($cat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Imagen actual (si existe) -->
        <?php if ($product->getImage()): ?>
            <p>Imagen actual:</p>
            <img src="<?= BASE_URL . htmlspecialchars($product->getImage()) ?>" alt="Imagen actual" width="100">
        <?php endif; ?>

        <!-- Campo para subir nueva imagen (si deseas permitir cambiarla) -->
        <label for="image">Nueva imagen (opcional):</label>
        <input type="file" id="image" name="image" accept="image/*">

        <!-- Botón para enviar el formulario -->
        <button type="submit">Actualizar</button>
    </form>

    <!-- Enlace para volver al listado -->
    <a href="<?= BASE_URL ?>products/list">Volver a la lista de productos</a>
</body>
</html>

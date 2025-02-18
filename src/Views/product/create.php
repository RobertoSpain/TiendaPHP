<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <!-- Enlace al archivo CSS para estilos -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Título de la página -->
        <h1>Crear un producto nuevo</h1>
        <?php 
        /* 
         * Comprueba si existe un mensaje de éxito en la sesión.
         * Si existe, lo muestra y luego lo elimina de la sesión.
         */
        if (isset($_SESSION['success_message'])): ?>
            <div class="notification success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php 
        /* 
         * Comprueba si existe un mensaje de error en la sesión.
         * Si existe, lo muestra y luego lo elimina de la sesión.
         */
        if (isset($_SESSION['error_message'])): ?>
            <div class="notification error">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Formulario para crear un nuevo producto -->
        <form action="<?= BASE_URL ?>products/save" method="POST" enctype="multipart/form-data">
            <!-- Campo para el nombre del producto -->
            <label for="name">Nombre producto:</label>
            <input type="text" id="name" name="name" required>

            <!-- Campo para la descripción del producto -->
            <label for="description">Descripcion:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <!-- Campo para el precio del producto -->
            <label for="price">Precio:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <!-- Campo para la cantidad de stock del producto -->
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" min="0" required>

            <!-- Campo para seleccionar la categoría del producto -->
            <label for="category">Categoria:</label>
            <select id="category" name="category_id" required>
                <option value="">-- Selecciona la categoria --</option>
                <?php 
                /* 
                 * Itera sobre las categorías disponibles y las muestra como opciones en el select.
                 * Ajustado para usar la clave 'nombre' en vez de 'name'.
                 */
                foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>">
                        <?= htmlspecialchars($category['nombre']) ?> <!-- <--- Ajuste aquí -->
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Campo para subir una imagen del producto -->
            <label for="image">Imagen Producto:</label>
            <input type="file" id="image" name="image" accept="image/*">

            <!-- Botón para enviar el formulario y crear el producto -->
            <button type="submit" class="primary">Create Product</button>
        </form>

        <!-- Enlace para regresar a la lista de productos -->
        <a href="<?= BASE_URL ?>products/list">Back to Product List</a>
    </div>
</body>
</html>

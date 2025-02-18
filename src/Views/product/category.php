<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos por Categoría</title>
</head>
<body>

    <!-- Título de la página -->
    <h1>Productos de la Categoría</h1>

    <?php 
    /* 
     * Comprueba si existe un mensaje de éxito en la sesión.
     * Si existe, lo muestra en color verde y luego lo elimina de la sesión.
     */
    if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['success_message']) ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php 
    /* 
     * Comprueba si existe un mensaje de error en la sesión.
     * Si existe, lo muestra en color rojo y luego lo elimina de la sesión.
     */
    if (isset($_SESSION['error_message'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['error_message']) ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php 
    /* 
     * Comprueba si hay productos disponibles en la categoría.
     * Si hay productos, los muestra en una lista.
     * Si no, muestra un mensaje indicando que no hay productos disponibles.
     */
    if (!empty($products)): ?>
        <!-- Lista de productos -->
        <ul>
            <?php 
            /* 
             * Itera sobre los productos de la categoría y los muestra con sus detalles.
             */
            foreach ($products as $product): ?>
                <li>
                    <!-- Nombre del producto -->
                    <strong>Nombre:</strong> <?= htmlspecialchars($product->getName()) ?><br>
                    <!-- Precio del producto -->
                    <strong>Precio:</strong> <?= htmlspecialchars($product->getPrice()) ?><br>
                    <!-- Descripción del producto -->
                    <strong>Descripción:</strong> <?= htmlspecialchars($product->getDescription()) ?><br>
                    <!-- Imagen del producto -->
                    <img src="<?= BASE_URL ?>public/img/<?= htmlspecialchars($product->getImage()) ?>" 
                         alt="<?= htmlspecialchars($product->getName()) ?>" 
                         style="max-width: 100px; height: auto;"><br>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>

        <!-- Paginación -->
        <div class="pagination">
            <?php 
            /* 
             * Comprueba si hay una página anterior y muestra un enlace si es el caso.
             */
            if ($pagerfanta->hasPreviousPage()): ?>
                <a href="<?= BASE_URL ?>products/category/<?= $categoryId ?>?page=<?= $pagerfanta->getPreviousPage() ?>">Anterior</a>
            <?php endif; ?>

            <?php 
            /* 
             * Genera enlaces de paginación para todas las páginas disponibles.
             */
            foreach (range(1, $pagerfanta->getNbPages()) as $page): ?>
                <a href="<?= BASE_URL ?>products/category/<?= $categoryId ?>?page=<?= $page ?>"><?= $page ?></a>
            <?php endforeach; ?>

            <?php 
            /* 
             * Comprueba si hay una página siguiente y muestra un enlace si es el caso.
             */
            if ($pagerfanta->hasNextPage()): ?>
                <a href="<?= BASE_URL ?>products/category/<?= $categoryId ?>?page=<?= $pagerfanta->getNextPage() ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Mensaje si no hay productos en la categoría -->
        <p>No hay productos disponibles para esta categoría.</p>
    <?php endif; ?>

    <!-- Enlace para volver a la lista de categorías -->
    <a href="<?= BASE_URL ?>categoria/listar">Volver a Categorías</a>

</body>
</html>
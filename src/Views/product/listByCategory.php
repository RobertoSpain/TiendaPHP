<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos de la Categoría</title>
</head>
<body>
    
    <!-- Título con el nombre de la categoría -->
    <h1>Productos de la Categoría: <?= htmlspecialchars($categoryName) ?></h1>

    <!-- Mensajes de éxito o error -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['success_message']) ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['error_message']) ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Mostrar productos de la categoría -->
    <?php if (!empty($products)): ?>
        <ul>
            <?php foreach ($products as $product): ?>
                <li>
                    <strong>Nombre:</strong> <?= htmlspecialchars($product->getName()) ?><br>
                    <strong>Precio:</strong> <?= htmlspecialchars($product->getPrice()) ?> €<br>
                    <strong>Descripción:</strong> <?= htmlspecialchars($product->getDescription()) ?><br>
                    <!-- Mostramos la imagen usando la ruta guardada, sin anteponer 'public/img/' -->
                    <?php if ($product->getImage()): ?>
                        <img src="<?= BASE_URL . htmlspecialchars($product->getImage()) ?>" alt="<?= htmlspecialchars($product->getName()) ?>" style="max-width: 150px; height: auto;"><br>
                    <?php else: ?>
                        <em>Sin imagen</em><br>
                    <?php endif; ?>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>

        <!-- Paginación (si usas Pagerfanta, de lo contrario, elimínala) -->
        <div>
            <?php if ($pagerfanta->hasPreviousPage()): ?>
                <a href="<?= BASE_URL ?>products/category/<?= $categoryId ?>?page=<?= $pagerfanta->getPreviousPage() ?>">Anterior</a>
            <?php endif; ?>

            <?php foreach (range(1, $pagerfanta->getNbPages()) as $page): ?>
                <a href="<?= BASE_URL ?>products/category/<?= $categoryId ?>?page=<?= $page ?>"><?= $page ?></a>
            <?php endforeach; ?>

            <?php if ($pagerfanta->hasNextPage()): ?>
                <a href="<?= BASE_URL ?>products/category/<?= $categoryId ?>?page=<?= $pagerfanta->getNextPage() ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>No hay productos disponibles en esta categoría.</p>
    <?php endif; ?>

    <!-- Enlace para volver a la lista de categorías -->
    <a href="<?= BASE_URL ?>categoria/listar">Volver a Categorías</a>
</body>
</html>

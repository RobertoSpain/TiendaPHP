<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>

    <!-- Estilos inline para el botón -->
    <style>
        .btn-add-cart {
            display: inline-block;
            padding: 6px 10px;
            background-color: green;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .btn-add-cart:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>

    <h1>Product List</h1>

    <a href="<?= BASE_URL ?>admin/usuarios">Go to User Management</a><br><br>
    <a href="<?= BASE_URL ?>carrito/view">Go to Cart</a><br><br>

    <?php 
    // Mensajes de éxito o error
    if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['success_message']) ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['error_message']) ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (!empty($products)): ?>
        <ul>
            <?php foreach ($products as $product): ?>
                <li>
                    <strong>Name:</strong> <?= htmlspecialchars($product->getName()) ?><br>
                    <strong>Price:</strong> <?= htmlspecialchars($product->getPrice()) ?><br>
                    <strong>Category ID:</strong> <?= htmlspecialchars($product->getCategoryId()) ?><br>
                    <strong>Description:</strong> <?= htmlspecialchars($product->getDescription()) ?><br>
                    <strong>Stock:</strong> <?= htmlspecialchars($product->getStock()) ?><br>

                    <!-- Mostrar imagen si existe -->
                    <?php if ($product->getImage()): ?>
                        <img src="<?= BASE_URL . htmlspecialchars($product->getImage()) ?>"
                             alt="Product Image" width="100"><br>
                    <?php endif; ?>

                    <!-- Formulario GET para agregar 1 unidad al carrito -->
                    <form action="<?= BASE_URL ?>carrito/add/<?= $product->getId() ?>/1"
                          method="GET" style="display:inline;">
                        <button type="submit" class="btn-add-cart">
                            Add to Cart
                        </button>
                    </form>

                    <?php if (\Lib\Utils::isAdmin()): ?>
                        <div class="admin-actions">
                            <!-- Editar producto -->
                            <a href="<?= BASE_URL ?>products/edit?id=<?= $product->getId() ?>" 
                               class="btn btn-primary">Edit</a>

                            <!-- Eliminar producto -->
                            <form action="<?= BASE_URL ?>products/delete" 
                                  method="POST" 
                                  style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= $product->getId() ?>">
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this product?');">
                                    Delete
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>

</body>
</html>

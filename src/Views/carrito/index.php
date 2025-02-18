<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <style>
        /* Estilos básicos para que la tabla se vea un poco mejor */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        img {
            max-width: 80px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Carrito de Compras</h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['success_message']) ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['error_message']) ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (!empty($items)): ?>
        <table cellpadding="5">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Precio Unidad</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): 
                $product  = $item['product'];
                $qty      = $item['quantity'];
                $subtotal = $item['subtotal'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($product->getName()) ?></td>
                    <td>
                        <?php if ($product->getImage()): ?>
                            <img src="<?= BASE_URL . htmlspecialchars($product->getImage()) ?>" alt="Producto">
                        <?php else: ?>
                            <em>Sin imagen</em>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($product->getPrice()) ?></td>
                    <td><?= $qty ?></td>
                    <td><?= $subtotal ?></td>
                    <td>
                        <!-- Enlace para aumentar la cantidad en 1 -->
                        <a href="<?= BASE_URL ?>carrito/add/<?= $product->getId() ?>/1">[ + ]</a>
                        <!-- Enlace para disminuir la cantidad en 1 -->
                        <a href="<?= BASE_URL ?>carrito/decrease/<?= $product->getId() ?>/1">[ - ]</a>
                        <!-- Enlace para quitar el producto completamente -->
                        <a href="<?= BASE_URL ?>carrito/remove/<?= $product->getId() ?>">[ Quitar ]</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total: <?= $totalPrice ?></h3>
    <?php else: ?>
        <p>El carrito está vacío.</p>
    <?php endif; ?>

    <p>
        <a href="<?= BASE_URL ?>carrito/clear">Vaciar Carrito</a> | 
        <a href="<?= BASE_URL ?>products/list">Seguir Comprando</a>
    </p>
</body>
</html>

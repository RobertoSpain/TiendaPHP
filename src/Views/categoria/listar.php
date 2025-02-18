<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listado de Categorías</title>
    <style>
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        .actions a, .actions form {
            display: inline-block;
            margin-right: 5px;
        }
        .actions button {
            background-color: #d9534f;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .actions button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <!-- Enlace al inicio -->
    <p><a href="<?= BASE_URL ?>">Inicio</a></p>

    <h1>Listado de Categorías</h1>

    <!-- Mensajes de éxito o error -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['success_message']) ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['error_message']) ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (!empty($categorias)): ?>
        <ul>
            <?php foreach ($categorias as $categoria): ?>
                <li>
                    <?= htmlspecialchars($categoria->getNombre()) ?>
                    <!-- Enlace para ver los productos de la categoría (opcional) -->
                    <a href="<?= BASE_URL ?>products/category/<?= htmlspecialchars($categoria->getId()) ?>">Ver Productos</a>
                    <!-- Enlace para editar la categoría -->
                    <a href="<?= BASE_URL ?>categoria/editar/<?= htmlspecialchars($categoria->getId()) ?>">Editar</a>
                    <!-- Formulario para eliminar la categoría -->
                    <div class="actions">
                        <form action="<?= BASE_URL ?>categoria/eliminar/<?= htmlspecialchars($categoria->getId()) ?>" method="POST" style="display:inline;">
                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta categoría?');">Borrar</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay categorías disponibles.</p>
    <?php endif; ?>

    <p><a href="<?= BASE_URL ?>categoria/crear">Crear Nueva Categoría</a></p>
</body>
</html>

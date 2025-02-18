<!-- Título de la página -->
<h1>Crear Categoría</h1>

<?php 
/* 
 * Comprueba si existe un mensaje de error en la sesión.
 * Si existe, lo muestra en rojo y luego lo elimina de la sesión.
 */
if (isset($_SESSION['error_message'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['error_message']) ?></p>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<!-- Formulario para crear una nueva categoría -->
<form action="<?= BASE_URL ?>categoria/crear" method="POST">
    <!-- Campo para el nombre de la categoría -->
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>

    <!-- Botón para enviar el formulario -->
    <button type="submit">Crear Categoría</button>
</form>

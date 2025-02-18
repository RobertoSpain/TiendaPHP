<?php 
/* 
 * Comprueba si existe un mensaje de éxito para el registro en la sesión.
 * Si existe, lo muestra y luego lo elimina de la sesión.
 */
if (isset($_SESSION['register_success'])): ?>
    <p style="color: green;"><?= htmlspecialchars($_SESSION['register_success']) ?></p>
    <?php unset($_SESSION['register_success']); ?>
<?php endif; ?>

<?php 
/* 
 * Comprueba si existe un mensaje de error para el registro en la sesión.
 * Si existe, lo muestra y luego lo elimina de la sesión.
 */
if (isset($_SESSION['register_error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['register_error']) ?></p>
    <?php unset($_SESSION['register_error']); ?>
<?php endif; ?>

<?php 
/* 
 * Comprueba si existe un mensaje de error para el inicio de sesión en la sesión.
 * Si existe, lo muestra y luego lo elimina de la sesión.
 */
if (isset($_SESSION['login_error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['login_error']) ?></p>
    <?php unset($_SESSION['login_error']); ?>
<?php endif; ?>

<!-- Formulario de inicio de sesión -->
<h3>Iniciar Sesión</h3>
<form action="<?= BASE_URL ?>/login" method="POST">
    <!-- Campo para el correo electrónico -->
    <label for="email">Correo Electrónico:</label>
    <input type="email" id="email" name="email" required>

    <!-- Campo para la contraseña -->
    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>

    <!-- Botón para enviar el formulario -->
    <button type="submit">Iniciar Sesión</button>
</form>

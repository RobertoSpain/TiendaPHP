<!-- Formulario para registrarse -->
<h3>Registrarse</h3>
<form action="<?= BASE_URL ?>usuario/registrar" method="POST">
    <!-- Campo para el nombre del usuario -->
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="data[nombre]">

    <!-- Campo para los apellidos del usuario -->
    <label for="apellidos">Apellidos</label>
    <input type="text" id="apellidos" name="data[apellidos]">

    <!-- Campo para el correo electrónico del usuario -->
    <label for="email">Correo Electrónico</label>
    <input type="email" id="email" name="data[email]">

    <!-- Campo para la contraseña del usuario -->
    <label for="password">Contraseña</label>
    <input type="password" id="password" name="data[password]">

    <!-- Campo para seleccionar el rol (visible solo para administradores) -->
    <?php 
    /* 
     * Si el usuario actual tiene el rol de administrador, 
     * muestra un campo de selección para asignar el rol al nuevo usuario.
     */
    if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <label for="role">Rol</label>
        <select id="role" name="data[role]">
            <option value="user">Usuario</option>
            <option value="admin">Administrador</option>
        </select>
    <?php endif; ?>

    <!-- Botón para enviar el formulario -->
    <button type="submit">Enviar</button>
</form>

<!-- Mensaje de éxito del registro -->
<?php 
/* 
 * Si existe un mensaje de éxito en la sesión, lo muestra
 * y luego lo elimina de la sesión.
 */
if (isset($_SESSION['register_success'])): ?>
    <p style="color: green;"><?= $_SESSION['register_success'] ?></p>
    <?php unset($_SESSION['register_success']); ?>
<?php endif; ?>

<!-- Mensaje de error del registro -->
<?php 
/* 
 * Si existe un mensaje de error en la sesión, lo muestra
 * y luego lo elimina de la sesión.
 */
if (isset($_SESSION['register_error'])): ?>
    <p style="color: red;"><?= $_SESSION['register_error'] ?></p>
    <?php unset($_SESSION['register_error']); ?>
<?php endif; ?>

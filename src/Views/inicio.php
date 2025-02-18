<?php
/* 
 * Incluye el archivo del encabezado de la página (header.php).
 * Esto asegura que la página tenga una estructura uniforme en todas las vistas.
 */
require_once __DIR__ . '/layout/header.php';
?>

<!-- Contenido principal de la página -->
<div class="container">
    <!-- Título de la página de inicio -->
    <h1>Bienvenido a miTienda</h1>
    <!-- Mensaje de bienvenida -->
    <p>Esta es tu página de inicio.</p>
</div>

<?php
/* 
 * Incluye el archivo del pie de página (footer.php).
 * Esto permite reutilizar el pie de página en todas las vistas del sitio.
 */
require_once __DIR__ . '/layout/footer.php';
?>

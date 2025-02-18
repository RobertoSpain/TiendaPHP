<!-- footer.php -->
<style>
    footer {
        background-color: #333; /* Color oscuro para el fondo */
        color: white; /* Texto en blanco */
        padding: 20px 0; /* Espaciado vertical */
    }
    .footer-container {
        display: flex; /* Usamos Flexbox para la alineación horizontal */
        justify-content: space-between; /* Espaciado entre las columnas */
        align-items: flex-start; /* Alinear los elementos al inicio verticalmente */
        flex-wrap: wrap; /* Permitir que las columnas se ajusten en pantallas pequeñas */
        max-width: 1200px; /* Ancho máximo del footer */
        margin: 0 auto; /* Centramos el contenido */
    }
    .footer-column {
        flex: 1; /* Todas las columnas ocupan el mismo espacio */
        margin: 10px; /* Espaciado entre columnas */
        min-width: 150px; /* Ancho mínimo para evitar que colapse */
    }
    .footer-column h3 {
        margin-bottom: 10px; /* Espaciado debajo de los títulos */
        font-size: 1.2em; /* Tamaño del texto del título */
    }
    .footer-column a {
        color: white; /* Enlaces en blanco */
        text-decoration: none; /* Sin subrayado */
        display: block; /* Cada enlace en una nueva línea */
        margin-bottom: 5px; /* Espaciado entre enlaces */
    }
    .footer-column a:hover {
        text-decoration: underline; /* Subrayado al pasar el ratón */
    }
    .social-icons a {
        font-size: 1.5em; /* Tamaño más grande para los íconos */
        margin-right: 10px; /* Espaciado entre íconos */
        color: white; /* Íconos en blanco */
    }
    .social-icons a:hover {
        color: #aaa; /* Cambiar a gris claro al pasar el ratón */
    }
</style>

<footer>
    <div class="footer-container">
        <div class="footer-column">
            <h3>Ayuda</h3>
            <a href="#" aria-label="Preguntas frecuentes">Preguntas frecuentes</a>
            <a href="#" aria-label="Política de envío">Política de envío</a>
        </div>
        <div class="footer-column">
            <h3>Nosotros</h3>
            <a href="#" aria-label="Quiénes somos">Quiénes somos</a>
            <a href="#" aria-label="Blog">Blog</a>
        </div>
        <div class="footer-column">
            <h3>Contacto</h3>
            <a href="#" aria-label="Enviar un email">Email</a>
            <a href="#" aria-label="Contactar por teléfono">Teléfono</a>
        </div>
        <div class="footer-column">
            <h3>Síguenos</h3>
            <div class="social-icons">
                <a href="#" aria-label="Instagram">📷</a>
                <a href="#" aria-label="Facebook">📘</a>
                <a href="#" aria-label="Twitter">🐦</a>
            </div>
        </div>
        <div class="footer-column">
            <h3>Métodos de Pago</h3>
            <div class="Pago">
                <a href="#" aria-label="Visa">💳</a>
                <a href="#" aria-label="Mastercard">🏦</a>
                <a href="#" aria-label="Paypal">🅿</a>
            </div>
        </div>
    </div>
</footer>


<!-- Cierre de etiquetas -->
</body>
</html>

<?php

namespace Lib;

use src\Controllers\ErrorController;

class Pages
{
    /**
     * Renderiza una vista.
     *
     * @param string $pageName Nombre de la vista a cargar (sin extensión .php).
     * @param array|null $params Parámetros opcionales que serán pasados a la vista.
     */
    public function render(string $pageName, array $params = null): void
    {
        /* Checkpoint de entrada al método render */
        error_log("Checkpoint: Entrando a Pages::render");
        error_log("Intentando cargar la vista: $pageName");

        /* Si hay parámetros, los convierte en variables disponibles para la vista */
        if ($params != null) {
            foreach ($params as $name => $value) {
                $$name = $value;
            }
        }

        /* Definir la ruta base de las vistas */
        $basePath = dirname(__DIR__) . "/Views";
        $viewPath = $basePath . "/$pageName.php";

        /* Verificar si la vista existe */
        if (!file_exists($viewPath)) {
            error_log("Checkpoint: La vista no existe en $viewPath");
            error_log("Intentando instanciar ErrorController...");

            /* Instanciar el controlador de errores y mostrar la página 404 */
            $errorController = new ErrorController();
            $errorController->error404("La vista '$pageName' no fue encontrada en '$viewPath'.");

            return;
        }

        error_log("Checkpoint: La vista $pageName existe. Cargando...");

        /* Incluir el layout con header y footer, y cargar la vista solicitada */
        require_once $basePath . "/layout/header.php";
        require_once $viewPath;
        require_once $basePath . "/layout/footer.php";
    }
}
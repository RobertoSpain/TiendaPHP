<?php
require_once __DIR__ . '/../config/config.php';


/* 
 * Inicia sesión para manejar variables de sesión.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* 
 * Configuración de errores para desarrollo.
 * 
 * Muestra todos los errores y advertencias en pantalla.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Dotenv\Dotenv;
use Routes\Routes;

/* 
 * Cargar el autoloader de Composer.
 * 
 * Permite cargar automáticamente las clases necesarias.
 */
require_once __DIR__ . '/../vendor/autoload.php';

/* 
 * Cargar la configuración del archivo .env usando safeLoad.
 */
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

/* 
 * Forzar las variables del .env a sobrescribir las globales.
 * 
 * Si alguna variable no está definida en el .env, se establece un valor por defecto.
 */
$_ENV['SERVERNAME'] = $_ENV['SERVERNAME'] ?? 'localhost';
$_ENV['USERNAME'] = $_ENV['USERNAME'] ?? 'root';
$_ENV['PASSWORD'] = $_ENV['PASSWORD'] ?? '';
$_ENV['DATABASE'] = $_ENV['DATABASE'] ?? 'tienda';
$_ENV['DB_CHARSET'] = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

foreach ($_ENV as $key => $value) {
    $_SERVER[$key] = $value;
}

/* 
 * Verificar si las variables del entorno están cargadas correctamente.
 * 
 * Si falta alguna variable crítica, se lanza una excepción.
 */
if (empty($_ENV['SERVERNAME']) || empty($_ENV['USERNAME']) || empty($_ENV['DATABASE']) || empty($_ENV['DB_CHARSET'])) {
    throw new Exception('Error: No se pudieron cargar las variables de entorno. Verifica el archivo .env.');
}

/* 
 * Llama al enrutador principal para iniciar la aplicación.
 */
Routes::index();

<?php 
namespace Lib;

class Utils
{
    /**
     * Método para verificar si el usuario actual es administrador
     * @return bool Devuelve true si el usuario tiene el rol de administrador, false en caso contrario
     */
    public static function isAdmin(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Método para calcular el total del carrito de compras
     * @param array $productos Array de productos en el carrito
     * @return float Devuelve el total de la compra basado en los precios y cantidades de los productos
     */
    public static function calculateCartTotal(array $productos): float
    {
        $total = 0.0;

        foreach ($productos as $producto) {
            if (isset($producto->quantity)) {
                $total += $producto->price * $producto->quantity;
            }
        }

        return $total;
    }

    /**
     * Método para validar texto
     * @param string|null $text Texto a validar
     * @param int $maxLength Longitud máxima permitida (por defecto 255)
     * @return bool Devuelve true si el texto es válido, false en caso contrario
     */
    public static function validateText(?string $text, int $maxLength = 255): bool
    {
        return !empty($text) && mb_strlen($text) <= $maxLength && preg_match('/^[\w\sáéíóúÁÉÍÓÚñÑ,.!?-]+$/u', $text);
    }

    /**
     * Método para validar números
     * @param mixed $number Número a validar
     * @param float|int|null $min Valor mínimo permitido (opcional)
     * @param float|int|null $max Valor máximo permitido (opcional)
     * @return bool Devuelve true si el número es válido, false en caso contrario
     */
    public static function validateNumber($number, $min = null, $max = null): bool
    {
        if (!is_numeric($number)) {
            return false;
        }
        if ($min !== null && $number < $min) {
            return false;
        }
        if ($max !== null && $number > $max) {
            return false;
        }
        return true;
    }

    /**
     * Método para validar correos electrónicos
     * @param string|null $email Correo electrónico a validar
     * @return bool Devuelve true si el correo es válido, false en caso contrario
     */
    public static function validateEmail(?string $email): bool
    {
        return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Método para validar imágenes subidas
     * @param array|null $file Archivo de imagen a validar
     * @param array $allowedTypes Tipos MIME permitidos
     * @return bool Devuelve true si la imagen es válida, false en caso contrario
     */
    public static function validateImage(?array $file, array $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']): bool
    {
        return isset($file['tmp_name'], $file['type'], $file['size']) &&
               in_array($file['type'], $allowedTypes) &&
               $file['size'] > 0;
    }

    /**
     * Método para sanitizar texto
     * @param string|null $text Texto a sanitizar
     * @param int $maxLength Longitud máxima permitida (por defecto 255)
     * @return string Texto sanitizado
     */
    public static function sanitizeText(?string $text, int $maxLength = 255): string
    {
        $text = htmlspecialchars(trim($text ?? ''));
        return mb_substr($text, 0, $maxLength);
    }

    /**
     * Método para validar contraseñas
     * @param string|null $password Contraseña a validar
     * @param int $minLength Longitud mínima requerida (por defecto 8)
     * @return bool Devuelve true si la contraseña es válida, false en caso contrario
     */
    public static function validatePassword(?string $password, int $minLength = 8): bool
    {
        return !empty($password) && mb_strlen($password) >= $minLength;
    }

    /**
     * Método para validar tamaños de imágenes
     * @param array|null $file Archivo de imagen a validar
     * @param int $maxSize Tamaño máximo permitido en bytes (por defecto 2 MB)
     * @return bool Devuelve true si el tamaño de la imagen es válido, false en caso contrario
     */
    public static function validateImageSize(?array $file, int $maxSize = 2097152): bool
    {
        return isset($file['size']) && $file['size'] <= $maxSize;
    }

    /**
     * Método para validar parámetros de paginación
     * @param int|null $page Número de página
     * @param int|null $limit Límite de resultados por página
     * @param int $maxLimit Límite máximo permitido (por defecto 100)
     * @return bool Devuelve true si los parámetros son válidos, false en caso contrario
     */
    public static function validatePagination(?int $page, ?int $limit, int $maxLimit = 100): bool
    {
        return self::validateNumber($page, 1) && self::validateNumber($limit, 1, $maxLimit);
    }

    /**
     * Método para validar credenciales de configuración
     * @param array $config Array de configuraciones (e.g., ['DB_HOST' => 'localhost'])
     * @return bool Devuelve true si todas las configuraciones son válidas, false en caso contrario
     */
    public static function validateConfig(array $config): bool
    {
        foreach ($config as $key => $value) {
            if (empty($value)) {
                error_log("Configuración faltante o inválida: $key");
                return false;
            }
        }
        return true;
    }

    /**
     * Método para generar mensajes consistentes
     * @param string $message Mensaje base
     * @param string $type Tipo de mensaje (e.g., 'error', 'success')
     * @return string Mensaje formateado
     */
    public static function formatMessage(string $message, string $type = 'info'): string
    {
        $prefix = match ($type) {
            'error' => '[ERROR] ',
            'success' => '[SUCCESS] ',
            'warning' => '[WARNING] ',
            default => '[INFO] ',
        };

        return $prefix . $message;
    }
}
?>
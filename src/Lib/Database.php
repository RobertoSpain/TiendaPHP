<?php 

namespace Lib;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Clase para gestionar la conexión a la base de datos y ejecutar consultas
 */
class Database {
    /**
     * Propiedades de la conexión
     */
    private ?PDO $conexion = null; // Conexión PDO
    private mixed $resultado; // Resultado de la consulta
    private string $host = 'localhost';
    private string $user = 'root';
    private string $pass = '';
    private string $db = 'miTienda';

    /**
     * Constructor: Inicializa las variables de conexión y establece la conexión
     */
    public function __construct()
    {
        $this->host = $_ENV['DB_SERVERNAME'] ?? 'localhost';
        $this->user = $_ENV['DB_USERNAME'] ?? 'root';
        $this->pass = $_ENV['DB_PASSWORD'] ?? '';
        $this->db = $_ENV['DB_DATABASE'] ?? 'miTienda';

        // Establecer la conexión
        $this->conexion = $this->conectar();
    }

    /**
     * Establece la conexión a la base de datos
     * @return PDO Devuelve el objeto PDO para interactuar con la base de datos
     */
    private function conectar(): PDO {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";

            // Opciones de configuración para PDO
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Manejo de errores como excepciones
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Devuelve resultados como array asociativo
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4", // Configuración de caracteres
            ];

            // Crear la conexión PDO
            return new PDO($dsn, $this->user, $this->pass, $opciones);
        } catch (PDOException $e) {
            throw new PDOException('Error al conectar a la base de datos: ' . $e->getMessage());
        }
    }

    /**
     * Obtener la conexión PDO actual.
     * 
     * @return PDO Devuelve la instancia de la conexión PDO.
     */
    public function getConnection(): PDO {
        return $this->conexion;
    }

    /**
     * Ejecuta una consulta preparada
     * @param string $consultaSQL Consulta SQL preparada
     * @return PDOStatement Devuelve la consulta preparada
     */
    public function prepararConsulta(string $consultaSQL): PDOStatement {
        return $this->conexion->prepare($consultaSQL);
    }

    /**
     * Ejecuta una consulta directa (no preparada)
     * @param string $consultaSQL Consulta SQL a ejecutar
     * @return void
     */
    public function ejecutarConsulta(string $consultaSQL): void {
        $this->resultado = $this->conexion->query($consultaSQL);
    }

    /**
     * Extrae un registro del resultado actual
     * @return mixed Devuelve el registro como array o false si no hay más registros
     */
    public function obtenerRegistro(): mixed {
        return $this->resultado->fetch();
    }

    /**
     * Extrae todos los registros coincidentes con la consulta
     * @return array Devuelve un array con todos los registros
     */
    public function obtenerTodosLosRegistros(): array {
        return $this->resultado->fetchAll();
    }

    /**
     * Devuelve el número de filas afectadas por la última consulta
     * @return int Número de filas afectadas
     */
    public function filasModificadas(): int {
        return $this->resultado->rowCount();
    }

    /**
     * Devuelve el último ID insertado
     * @return int Último ID insertado en la base de datos
     */
    public function ultimoIdInsertado(): int {
        return (int) $this->conexion->lastInsertId();
    }

    /**
     * Cierra la conexión a la base de datos
     * @return void
     */
    public function cerrarConexion(): void {
        $this->conexion = null;
    }

    /**
     * Inicia una transacción
     * @return bool Devuelve true si la transacción se inicia correctamente
     */
    public function iniciarTransaccion(): bool {
        return $this->conexion->beginTransaction();
    }

    /**
     * Realiza un commit de la transacción actual
     * @return bool Devuelve true si el commit fue exitoso
     */
    public function confirmarTransaccion(): bool {
        return $this->conexion->commit();
    }

    /**
     * Realiza un rollback de la transacción actual
     * @return bool Devuelve true si el rollback fue exitoso
     */
    public function deshacerTransaccion(): bool {
        return $this->conexion->rollBack();
    }
}
?>
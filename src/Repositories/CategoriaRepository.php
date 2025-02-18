<?php

namespace Repositories;

use Models\Categoria;
use PDO;
use PDOException;

class CategoriaRepository
{
    private PDO $db;

    /**
     * Constructor de la clase, establece la conexión con la base de datos.
     *
     * @param PDO $db Conexión PDO.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Encuentra una categoría por su ID.
     * 
     * @param int $id ID de la categoría a buscar.
     * @return Categoria|null Retorna un objeto Categoria si existe, o null si no se encuentra.
     * @throws PDOException En caso de fallo en la consulta SQL.
     */
    public function findById(int $id): ?Categoria
    {
        try {
            $query = "SELECT * FROM categorias WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? Categoria::fromArray($result) : null;
        } catch (PDOException $e) {
            throw new PDOException('Error al obtener la categoría por ID: ' . $e->getMessage());
        }
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     * 
     * @param Categoria $categoria Objeto de tipo Categoria con los datos a guardar.
     * @return void
     * @throws PDOException En caso de fallo en la consulta SQL.
     */
    public function save(Categoria $categoria): void
    {
        try {
            $query = "INSERT INTO categorias (nombre) VALUES (:nombre)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nombre', $categoria->getNombre(), PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException('Error al guardar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene todas las categorías de la base de datos.
     * 
     * @return array Array de objetos Categoria.
     * @throws PDOException En caso de fallo en la consulta SQL.
     */
    public function findAll(): array
    {
        try {
            $query = "SELECT * FROM categorias";
            $stmt = $this->db->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn($data) => Categoria::fromArray($data), $result);
        } catch (PDOException $e) {
            throw new PDOException('Error al obtener las categorías: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza una categoría existente en la base de datos.
     * 
     * @param int $id ID de la categoría a actualizar.
     * @param string $name Nuevo nombre de la categoría.
     * @return bool Devuelve true si la actualización fue exitosa.
     * @throws PDOException En caso de fallo en la consulta SQL.
     */
    public function update(int $id, string $name): bool
    {
        try {
            $query = "UPDATE categorias SET nombre = :nombre WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nombre', $name, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException('Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Elimina una categoría existente de la base de datos.
     * 
     * @param int $id ID de la categoría a eliminar.
     * @return bool Devuelve true si la eliminación fue exitosa.
     * @throws PDOException En caso de fallo en la consulta SQL.
     */
    public function delete(int $id): bool
    {
        try {
            $query = "DELETE FROM categorias WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException('Error al eliminar la categoría: ' . $e->getMessage());
        }
    }
}

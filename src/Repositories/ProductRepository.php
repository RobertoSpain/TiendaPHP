<?php

namespace Repositories;

use Models\Product;
use PDO;
use PDOException;

class ProductRepository
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
     * Obtener todos los productos.
     * 
     * @return array Lista de productos.
     * @throws PDOException
     */
    public function findAll(): array
    {
        try {
            $query = "SELECT * FROM productos";
            $stmt = $this->db->query($query);
            $products = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $products[] = $this->mapToProduct($row);
            }

            return $products;
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener los productos: " . $e->getMessage());
        }
    }

    /**
     * Obtener un producto por su ID.
     * 
     * @param int $id ID del producto.
     * @return Product|null El producto encontrado o null si no existe.
     * @throws PDOException
     */
    public function findById(int $id): ?Product
    {
        try {
            $query = "SELECT * FROM productos WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? $this->mapToProduct($row) : null;
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener el producto: " . $e->getMessage());
        }
    }

    /**
     * Obtener productos por categoría.
     * 
     * @param int $categoryId ID de la categoría.
     * @param int $limit Cantidad de productos a mostrar.
     * @param int $offset Desde dónde empezar a contar.
     * @return array Lista de productos filtrados por categoría.
     * @throws PDOException
     */
    public function findByCategory(int $categoryId, int $limit, int $offset): array
    {
        try {
            $query = "SELECT * FROM productos WHERE categoria_id = :categoria_id LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':categoria_id', $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $products = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $products[] = $this->mapToProduct($row);
            }

            return $products;
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener productos por categoría: " . $e->getMessage());
        }
    }

    /**
     * Contar cuántos productos hay en una categoría.
     * 
     * @param int $categoryId ID de la categoría.
     * @return int Número de productos en la categoría.
     * @throws PDOException
     */
    public function countByCategory(int $categoryId): int
    {
        try {
            $query = "SELECT COUNT(*) AS total FROM productos WHERE categoria_id = :categoria_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':categoria_id', $categoryId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)$result['total'];
        } catch (PDOException $e) {
            throw new PDOException("Error al contar productos por categoría: " . $e->getMessage());
        }
    }

    /**
     * Guardar un producto (Insertar o Actualizar).
     * 
     * @param Product $product Producto a guardar.
     * @return bool Devuelve true si la operación fue exitosa.
     * @throws PDOException
     */
    public function save(Product $product): bool
    {
        try {
            if ($product->getId()) {
                // Actualizar producto existente
                return $this->update($product->getId(), [
                    'name' => $product->getName(),
                    'description' => $product->getDescription(),
                    'price' => $product->getPrice(),
                    'stock' => $product->getStock(),
                    'category_id' => $product->getCategoryId(),
                    'image' => $product->getImage(),
                ]);
            } else {
                // Insertar un nuevo producto
                $query = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, imagen) 
                          VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id, :imagen)";
                $stmt = $this->db->prepare($query);
                return $stmt->execute([
                    ':nombre' => $product->getName(),
                    ':descripcion' => $product->getDescription(),
                    ':precio' => $product->getPrice(),
                    ':stock' => $product->getStock(),
                    ':categoria_id' => $product->getCategoryId(),
                    ':imagen' => $product->getImage(),
                ]);
            }
        } catch (PDOException $e) {
            throw new PDOException("Error al guardar el producto: " . $e->getMessage());
        }
    }

    /**
     * Obtener todas las categorías.
     * 
     * @return array Lista de categorías.
     * @throws PDOException
     */
    public function findAllCategories(): array
    {
        try {
            $query = "SELECT * FROM categorias";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener las categorías: " . $e->getMessage());
        }
    }

    /**
     * Obtener el nombre de la categoría por su ID.
     * 
     * @param int $categoryId ID de la categoría.
     * @return string|null Nombre de la categoría o null si no se encuentra.
     * @throws PDOException
     */
    public function getCategoryNameById(int $categoryId): ?string
    {
        try {
            $query = "SELECT nombre FROM categorias WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $categoryId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['nombre'] : null;
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener el nombre de la categoría: " . $e->getMessage());
        }
    }

    /**
     * Actualizar un producto.
     * 
     * @param int $id ID del producto a actualizar.
     * @param array $data Datos a actualizar.
     * @return bool True si la actualización fue exitosa.
     * @throws PDOException
     */
    public function update(int $id, array $data): bool
    {
        try {
            $query = "UPDATE productos 
                      SET nombre = :name, descripcion = :description, precio = :price, 
                          stock = :stock, categoria_id = :category_id, imagen = :image 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':price' => $data['price'],
                ':stock' => $data['stock'],
                ':category_id' => $data['category_id'],
                ':image' => $data['image'],
            ]);
        } catch (PDOException $e) {
            throw new PDOException("Error al actualizar el producto: " . $e->getMessage());
        }
    }

    /**
     * Eliminar un producto.
     * 
     * @param int $id ID del producto a eliminar.
     * @return bool True si la eliminación fue exitosa.
     * @throws PDOException
     */
    public function delete(int $id): bool
    {
        try {
            $query = "DELETE FROM productos WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Error al eliminar el producto: " . $e->getMessage());
        }
    }

    /**
     * Convierte una fila de la base de datos en un objeto Product.
     * 
     * @param array $row Fila obtenida de la base de datos.
     * @return Product
     */
    private function mapToProduct(array $row): Product
    {
        return new Product(
            id: (int)$row['id'],
            name: $row['nombre'],
            description: $row['descripcion'],
            price: (float)$row['precio'],
            stock: (int)$row['stock'],
            categoryId: (int)$row['categoria_id'],
            image: $row['imagen'] ?? ''
        );
    }
}

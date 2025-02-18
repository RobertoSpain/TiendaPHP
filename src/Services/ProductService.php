<?php

namespace Services;

use Repositories\ProductRepository;
use Models\Product;
use Lib\Utils;

class ProductService
{
    private ProductRepository $repository;

    /* 
     * Constructor de la clase ProductService.
     * 
     * @param ProductRepository $repository Repositorio de productos.
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /* 
     * Obtener todos los productos disponibles.
     * 
     * @return array Lista de productos.
     */
    public function getAllProducts(): array
    {
        return $this->repository->findAll();
    }

    /* 
     * Obtener todas las categorías disponibles.
     * 
     * @return array Lista de categorías.
     */
    public function getAllCategories(): array
    {
        return $this->repository->findAllCategories();
    }

    /* 
     * Obtener productos filtrados por categoría.
     * 
     * @param int $categoryId ID de la categoría.
     * @param int $limit Número de productos por página.
     * @param int $offset Número de productos a omitir.
     * @return array Lista de productos.
     */
    public function getProductsByCategory(int $categoryId, int $limit = 10, int $offset = 0): array
    {
        return $this->repository->findByCategory($categoryId, $limit, $offset);
    }

    /* 
     * Contar productos en una categoría.
     * 
     * @param int $categoryId ID de la categoría.
     * @return int Cantidad de productos en la categoría.
     */
    public function countProductsByCategory(int $categoryId): int
    {
        return $this->repository->countByCategory($categoryId);
    }

    /* 
     * Obtener un producto por su ID.
     * 
     * @param int $id ID del producto.
     * @return Product|null Producto encontrado, o null si no existe.
     */
    public function getProductById(int $id): ?Product
    {
        return $this->repository->findById($id);
    }

    /* 
     * Crear un producto nuevo.
     * 
     * @param array $data Datos del producto.
     * @throws \InvalidArgumentException Si los datos son inválidos.
     */
    public function createProduct(array $data): void
    {
        $this->validateProductData($data);

        $product = new Product(
            id: null,
            name: $data['name'],
            description: $data['description'],
            price: (float) $data['price'],
            stock: (int) $data['stock'],
            categoryId: (int) $data['category_id'],
            image: $data['image'] ?? null
        );

        $this->repository->save($product);
    }

    /* 
     * Guardar o actualizar un producto.
     * 
     * @param array $data Datos del producto.
     * @return bool Devuelve true si el producto se guardó correctamente.
     * @throws \Exception Si los datos son inválidos.
     */
    public function saveProduct(array $data): bool
    {
        $this->validateProductData($data);

        $product = new Product(
            id: $data['id'] ?? null,
            name: $data['name'],
            description: $data['description'],
            price: (float) $data['price'],
            stock: (int) $data['stock'],
            categoryId: (int) $data['category_id'],
            image: $data['image'] ?? null
        );

        return $this->repository->save($product);
    }

    /* 
     * Actualizar un producto existente.
     * 
     * @param int $id ID del producto.
     * @param array $data Datos a actualizar.
     * @return bool True si la actualización fue exitosa.
     * @throws \Exception Si los datos son inválidos.
     */
    public function updateProduct(int $id, array $data): bool
    {
        if (!Utils::validateNumber($id, 1)) {
            throw new \Exception('ID de producto no válido.');
        }
    
        $this->validateProductData($data, true);
    
        $updateData = [
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? null,
            'stock' => $data['stock'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'image' => $data['image'] ?? null,
        ];
    
        return $this->repository->update($id, $updateData);
    }

    /* 
     * Eliminar un producto por su ID.
     * 
     * @param int $id ID del producto.
     * @return bool True si la eliminación fue exitosa.
     * @throws \Exception Si el producto no existe o el ID es inválido.
     */
    public function deleteProduct(int $id): bool
    {
        if (!Utils::validateNumber($id, 1)) {
            throw new \Exception('ID de producto no válido.');
        }

        return $this->repository->delete($id);
    }

    /* 
     * Validar datos de un producto.
     * 
     * @param array $data Datos a validar.
     * @param bool $isUpdate Indica si es una actualización.
     * @throws \InvalidArgumentException Si los datos son inválidos.
     */

     public function getCategoryNameById(int $categoryId): string
     {
         try {
             // Validar el ID
             if (!Utils::validateNumber($categoryId, 1)) {
                 throw new \InvalidArgumentException('ID de categoría no válido.');
             }
     
             // Intentar obtener el nombre de la categoría desde el repositorio
             $categoryName = $this->repository->getCategoryNameById($categoryId);
     
             // Validar si se encontró la categoría
             if (!$categoryName) {
                 throw new \Exception('Categoría no encontrada.');
             }
     
             return $categoryName;
         } catch (\PDOException $e) {
             throw new \Exception('Error al buscar la categoría en la base de datos: ' . $e->getMessage());
         } catch (\Exception $e) {
             throw new \Exception('Error inesperado: ' . $e->getMessage());
         }
     }
     
     


    private function validateProductData(array $data, bool $isUpdate = false): void
    {
        if (!$isUpdate || isset($data['name'])) {
            if (empty($data['name']) || !Utils::validateText($data['name'], 100)) {
                throw new \InvalidArgumentException('El nombre del producto no es válido.');
            }
        }

        if (!$isUpdate || isset($data['description'])) {
            if (empty($data['description']) || !Utils::validateText($data['description'], 500)) {
                throw new \InvalidArgumentException('La descripción del producto no es válida.');
            }
        }

        if (!$isUpdate || isset($data['price'])) {
            if (!Utils::validateNumber($data['price'], 0)) {
                throw new \InvalidArgumentException('El precio debe ser un número positivo.');
            }
        }

        if (!$isUpdate || isset($data['category_id'])) {
            if (!Utils::validateNumber($data['category_id'], 1)) {
                throw new \InvalidArgumentException('La categoría es inválida.');
            }
        }

        if (isset($data['stock']) && !Utils::validateNumber($data['stock'], 0)) {
            throw new \InvalidArgumentException('El stock debe ser un número no negativo.');
        }

        if (isset($data['image']) && !Utils::validateText($data['image'], 255)) {
            throw new \InvalidArgumentException('El nombre de la imagen no es válido.');
        }
    }
}

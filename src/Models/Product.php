<?php 
namespace Models;

class Product
{
    private ?int $id;
    private string $name;
    private string $description;
    private float $price;
    private int $stock;
    private int $categoryId;
    private ?string $image; 

    /* 
     * Propiedad para la cantidad del producto en el carrito (opcional).
     */
    public ?int $quantity = null;

    /* 
     * Constructor de la clase Product.
     * 
     * @param int|null $id ID del producto (opcional).
     * @param string $name Nombre del producto.
     * @param string $description Descripción del producto.
     * @param float $price Precio del producto.
     * @param int $stock Cantidad disponible en stock.
     * @param int $categoryId ID de la categoría del producto.
     * @param string|null $image URL o nombre del archivo de la imagen del producto (opcional).
     */
    public function __construct(
        ?int $id = null,
        string $name = '',
        string $description = '',
        float $price = 0.0,
        int $stock = 0,
        int $categoryId = 0,
        ?string $image = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->categoryId = $categoryId;
        $this->image = $image;
    }

    /* 
     * Obtener el ID del producto.
     * 
     * @return int|null ID del producto.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /* 
     * Obtener el nombre del producto.
     * 
     * @return string Nombre del producto.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /* 
     * Obtener la descripción del producto.
     * 
     * @return string Descripción del producto.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /* 
     * Obtener el precio del producto.
     * 
     * @return float Precio del producto.
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /* 
     * Obtener la cantidad de stock del producto.
     * 
     * @return int Cantidad en stock.
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /* 
     * Obtener el ID de la categoría del producto.
     * 
     * @return int ID de la categoría.
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /* 
     * Obtener la imagen del producto.
     * 
     * @return string|null Nombre o URL de la imagen del producto.
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /* 
     * Establecer el ID del producto.
     * 
     * @param int|null $id ID del producto.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /* 
     * Establecer el nombre del producto.
     * 
     * @param string $name Nombre del producto.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /* 
     * Establecer la descripción del producto.
     * 
     * @param string $description Descripción del producto.
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /* 
     * Establecer el precio del producto.
     * 
     * @param float $price Precio del producto.
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /* 
     * Establecer la cantidad de stock del producto.
     * 
     * @param int $stock Cantidad en stock.
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /* 
     * Establecer el ID de la categoría del producto.
     * 
     * @param int $categoryId ID de la categoría.
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /* 
     * Establecer la imagen del producto.
     * 
     * @param string|null $image Nombre o URL de la imagen del producto.
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /* 
     * Convertir el producto a un array asociativo.
     * 
     * @return array Datos del producto en formato array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->categoryId,
            'image' => $this->image,
        ];
    }

    /* 
     * Eliminar un producto de la base de datos.
     * 
     * @param int $id ID del producto a eliminar.
     * @return bool Devuelve true si la eliminación fue exitosa, false en caso contrario.
     */
    public function delete(int $id): bool
    {
        $database = new \Lib\Database(); 
        $db = $database->getConnection(); 
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
}
?>

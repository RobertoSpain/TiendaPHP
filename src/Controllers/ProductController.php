<?php

namespace Controllers;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Repositories\ProductRepository;
use Models\Product;
use Lib\Database;
use Lib\Pages;

class ProductController
{
    private ProductRepository $repo;
    private Pages $pages;

    public function __construct()
    {
        // Instancia de tu clase Database
        $database = new Database();
        
        // Obtenemos la conexión PDO
        $pdo = $database->getConnection();
        
        // Instanciamos el repositorio, pasándole el PDO
        $this->repo = new ProductRepository($pdo);
        
        // Instanciamos Pages (o la clase que uses para renderizar vistas)
        $this->pages = new Pages();
    }

    public function list(): void
    {
        // Recuperamos todos los productos
        $products = $this->repo->findAll();

        // Renderizamos la vista con los productos
        $this->pages->render("product/list", [
            'products' => $products
        ]);
    }

    public function create(): void
    {
        // 1. Obtener las categorías
        $categories = $this->repo->findAllCategories();
    
        // 2. Renderizar la vista con las categorías
        $this->pages->render("product/create", [
            'categories' => $categories
        ]);
    }
    
    public function save(): void
    {
        // Recogida de valores por POST
        $id          = $_POST['id']          ?? null;
        $name        = $_POST['name']        ?? '';
        $description = $_POST['description'] ?? '';
        $price       = $_POST['price']       ?? 0;
        $stock       = $_POST['stock']       ?? 0;
        $categoryId  = $_POST['category_id'] ?? 0;
    
        // 1. Procesar la imagen subidasi existe
        $imagePath = ''; // Valor por defecto si no se subió nada
    
        // Verificar que el input type="file" se haya subido sin error
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Nombre original del archivo
            $originalName = $_FILES['image']['name'];
            // Ruta temporal
            $tempPath = $_FILES['image']['tmp_name'];
    
            // Carpeta destino donde guardar la imagen (por ejemplo, en "public/uploads/images/")
            $uploadDir = __DIR__ . '/../../public/uploads/images/';
    
            // Si la carpeta no existe, la creamos
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            // Nombre único para el archivo (por ejemplo, time + nombre original)
            $newFileName = time() . '_' . $originalName;
    
            // Ruta final (en el sistema de archivos)
            $destination = $uploadDir . $newFileName;
    
            // Mover el archivo desde la carpeta temporal a la carpeta final
            if (move_uploaded_file($tempPath, $destination)) {
                // Guardamos la ruta relativa para almacenarla en la base de datos
                // Por ejemplo: "uploads/images/1676237840_mi_foto.jpg"
                $imagePath = 'uploads/images/' . $newFileName;
            } else {
                // Maneja el error si no se pudo mover el archivo
                error_log("No se pudo mover el archivo subido");
            }
        }
    
        // 2. Creamos el objeto Product con la ruta de imagen
        $product = new Product(
            id:         (int)$id,
            name:       $name,
            description:$description,
            price:      (float)$price,
            stock:      (int)$stock,
            categoryId: (int)$categoryId,
            image:      $imagePath  // <-- Aquí usamos la ruta generada
        );
    
        // 3. Guardamos a través del repositorio
        $this->repo->save($product);
    
        // 4. Redirigimos a la lista de productos
        header('Location: ' . BASE_URL . '/products/list');
        exit;
    }
    
    public function delete(): void
    {
        // Suponemos que el ID llega por POST
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->repo->delete((int)$id);
        }
        header('Location: ' . BASE_URL . '/products/list');
        exit;
    }

    public function edit(): void
    {
        // Agregamos un log para saber que estamos entrando al método
        error_log("Entrando a ProductController->edit()");
    
        // 1. Recuperar el "id" de la URL
        $id = $_GET['id'] ?? null;
    
        // Log para verificar el valor de $id
        error_log("Valor de id en edit(): " . var_export($id, true));
    
        if (!$id) {
            // Si no hay ID, redirige o muestra error
            header('Location: ' . BASE_URL . '/products/list');
            exit;
        }
    
        // 2. Buscar el producto en la base de datos
        $product = $this->repo->findById((int)$id);
        // Log para verificar si se encontró el producto
        if ($product) {
            error_log("Producto encontrado con id={$id}, nombre={$product->getName()}");
        } else {
            error_log("No se encontró el producto con id={$id}");
        }
    
        // 3. Si el producto no existe, redirige o muestra error
        if (!$product) {
            // Podrías mostrar un error 404 o un mensaje
            header('Location: ' . BASE_URL . '/error/');
            exit;
        }
    
        // 4. (Opcional) Obtener también las categorías, si necesitas un select
        $categories = $this->repo->findAllCategories();
        // Log para contar las categorías recuperadas
        error_log("Categorías encontradas: " . count($categories));
    
        // 5. Renderizar la vista "product/edit"
        error_log("Renderizando vista product/edit ...");
        $this->pages->render("product/edit", [
            'product' => $product,
            'categories' => $categories
        ]);
        error_log("Vista product/edit renderizada satisfactoriamente.");
    }
    
    
    public function update(): void
{
    $id          = $_POST['id']          ?? null;
    $name        = $_POST['name']        ?? '';
    $description = $_POST['description'] ?? '';
    $price       = $_POST['price']       ?? 0;
    $stock       = $_POST['stock']       ?? 0;
    $categoryId  = $_POST['category_id'] ?? 0;

    if (!$id) {
        header('Location: ' . BASE_URL . '/products/list');
        exit;
    }

    // 1. Buscamos el producto original en la base
    $product = $this->repo->findById((int)$id);
    if (!$product) {
        // Maneja error o redirige
        header('Location: ' . BASE_URL . '/error/');
        exit;
    }

    // 2. Actualizamos sus campos básicos (nombre, descripción, precio, etc.)
    $product->setName($name);
    $product->setDescription($description);
    $product->setPrice((float)$price);
    $product->setStock((int)$stock);
    $product->setCategoryId((int)$categoryId);

    // 3. Procesar la imagen si se sube
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tempPath      = $_FILES['image']['tmp_name'];
        $originalName  = $_FILES['image']['name'];
        $uploadDir     = __DIR__ . '/../../public/uploads/images/'; // Ajustar a tu carpeta real

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generar un nombre único para evitar colisiones
        $newFileName = time() . '_' . $originalName;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($tempPath, $destination)) {
            // Guardar la ruta relativa en la BD. Ej: "uploads/images/1675432100_foto.jpg"
            $newImagePath = 'uploads/images/' . $newFileName;

            // Si quieres, puedes borrar la imagen anterior del servidor:
            /*
            if ($product->getImage()) {
                $oldImageFile = __DIR__ . '/../../public/' . $product->getImage();
                if (file_exists($oldImageFile)) {
                    unlink($oldImageFile);
                }
            }
            */

            $product->setImage($newImagePath);
        }
    }

    // 4. Guardar el producto en BD. Si tu método save() detecta ID, hará un update
    $this->repo->save($product);

    // Redirigir de nuevo a la lista
    header('Location: ' . BASE_URL . '/products/list');
    exit;
}




public function listByCategory(int $categoryId): void
{
    $limit = 20;
    $offset = 0;

    $categoryName = $this->repo->getCategoryNameById($categoryId);
    $allProducts = $this->repo->findByCategory($categoryId, 9999, 0); // Todos, para la demo

    // Configuramos Pagerfanta
    $adapter = new ArrayAdapter($allProducts);
    $pagerfanta = new Pagerfanta($adapter);

    // Recogemos la página de $_GET si la usas
    $currentPage = $_GET['page'] ?? 1;
    $pagerfanta->setCurrentPage($currentPage);
    $pagerfanta->setMaxPerPage($limit);

    // Los productos de la página actual
    $products = $pagerfanta->getCurrentPageResults();

    $this->pages->render("product/listByCategory", [
        'categoryId'   => $categoryId,
        'categoryName' => $categoryName,
        'products'     => $products,
        'pagerfanta'   => $pagerfanta,
    ]);
}
}
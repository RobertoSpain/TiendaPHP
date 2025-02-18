<?php
namespace Controllers;

use Repositories\ProductRepository;
use Lib\Database;
use Lib\Pages;

class CarritoController
{
    private ProductRepository $repo;
    private Pages $pages;

    public function __construct()
    {
        // Conexión a la base
        $database = new Database();
        $pdo = $database->getConnection();
        // Repositorio para obtener datos de los productos
        $this->repo = new ProductRepository($pdo);

        // Clase para renderizar vistas
        $this->pages = new Pages();

        // Asegurar que la sesión esté iniciada
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        // Inicializar el carrito en sesión si no existe
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Muestra el contenido del carrito.
     */
    public function index()
    {
        // Obtener productos del carrito
        $cartItems = $_SESSION['cart']; // array [productId => cantidad]

        $productsInCart = [];
        $totalPrice = 0;

        // Recorrer cada entry del carrito
        foreach ($cartItems as $productId => $qty) {
            $product = $this->repo->findById((int)$productId);
            if ($product) {
                // Guardamos cantidad + datos del producto
                $item = [
                    'product' => $product,
                    'quantity' => $qty,
                    'subtotal' => $product->getPrice() * $qty
                ];
                $productsInCart[] = $item;
                $totalPrice += $item['subtotal'];
            }
        }

        // Renderizar vista "carrito/index" o "carrito/view"
        $this->pages->render("carrito/index", [
            'items' => $productsInCart,
            'totalPrice' => $totalPrice
        ]);
    }

    /**
     * Añade un producto al carrito.
     */
    public function add(int $productId, int $quantity = 1)
    {
        // Verificar si el producto existe (opcional)
        $product = $this->repo->findById($productId);
        if (!$product) {
            // Maneja error: producto no existe
            $_SESSION['error_message'] = "Producto no encontrado.";
            header('Location: ' . BASE_URL . 'products/list');
            exit;
        }

        // Agregar o actualizar la cantidad en el carrito
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }

        // Redirigir o mostrar vista
        $_SESSION['success_message'] = "Producto agregado al carrito.";
        header('Location: ' . BASE_URL . 'carrito/view');
        exit;
    }

    /**
     * Quita completamente un producto del carrito.
     */
    public function remove(int $productId)
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        $_SESSION['success_message'] = "Producto eliminado del carrito.";
        header('Location: ' . BASE_URL . 'carrito/view');
        exit;
    }

    /**
     * Disminuye la cantidad de un producto en el carrito.
     */
    public function decrease(int $productId, int $quantity = 1)
    {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] -= $quantity;
            if ($_SESSION['cart'][$productId] <= 0) {
                unset($_SESSION['cart'][$productId]);
            }
        }
        header('Location: ' . BASE_URL . 'carrito/view');
        exit;
    }

    /**
     * Vacía todo el carrito.
     */
    public function clear()
    {
        $_SESSION['cart'] = [];
        $_SESSION['success_message'] = "Carrito vaciado.";
        header('Location: ' . BASE_URL . 'carrito/view');
        exit;
    }
}

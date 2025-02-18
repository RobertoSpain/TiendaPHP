<?php

namespace Routes;

use Controllers\AuthController;
use Controllers\UserController; 
use Controllers\AdminController;
use Controllers\CategoriaController;
use Controllers\CarritoController;
use Controllers\ProductController;
use Lib\Router;  
use Src\Controllers\ErrorController;

class Routes {
    public static function index() {
        error_log("Checkpoint: Entrando a Routes::index");

        // Ruta principal (página de inicio): muestra el listado de productos
        Router::add('GET', '/', function () {
            error_log("Checkpoint: Mostrando la lista de productos en la página de inicio");
            (new ProductController())->list(); 
        });

        // Ruta de error
        Router::add('GET', '/error/', function () {
            error_log("Checkpoint: Ruta de error ejecutada");
            return (new ErrorController())->error404();
        }); 

        /*
         |-----------------------------------
         | AUTH (registro / login / logout)
         |-----------------------------------
         */
        Router::add('GET', '/register', function () {
            error_log("Checkpoint: Ruta GET /register ejecutada");
            (new AuthController())->register();
        });

        Router::add('POST', '/register', function () {
            error_log("Checkpoint: Ruta POST /register ejecutada");
            (new AuthController())->register();
        });

        Router::add('GET', '/login', function () {
            error_log("Checkpoint: Ruta GET /login ejecutada");
            (new AuthController())->login();
        });

        Router::add('POST', '/login', function () {
            error_log("Checkpoint: Ruta POST /login ejecutada");
            (new AuthController())->processLogin();
        });

        Router::add('GET', '/logout', function () {
            error_log("Checkpoint: Ruta GET /logout ejecutada");
            session_destroy();
            header('Location: ' . BASE_URL);
            exit;
        });

        /*
         |-----------------------------------
         | ADMIN
         |-----------------------------------
         */
        Router::add('GET', '/admin', function () {
            requireAdmin();
            (new AdminController())->index();
        });

        // Ruta para mostrar la lista de usuarios (GET)
        Router::add('GET', '/admin/usuarios', function () {
            requireAdmin();
            (new AdminController())->index();
        });

        // Ruta para crear un nuevo usuario (POST)
        Router::add('POST', '/admin/usuarios', function () {
            requireAdmin();
            (new AdminController())->createUser();
        });

        // Ruta para eliminar un usuario (POST)
        Router::add('POST', '/admin/eliminarUsuario/:id', function ($id) {
            requireAdmin();
            (new AdminController())->eliminarUsuario((int)$id);
        });

        /*
         |-----------------------------------
         | USER CONTROLLER
         |-----------------------------------
         */
        Router::add('GET', '/usuario/registrar', function () {
            error_log("Checkpoint: Ruta GET /usuario/registrar ejecutada");
            (new UserController())->mostrarFormularioRegistro();
        });

        Router::add('POST', '/usuario/registrar', function () {
            error_log("Checkpoint: Ruta POST /usuario/registrar ejecutada");
            (new UserController())->registrarUsuario();
        });

 



/*
 |-----------------------------------
 | CATEGORÍA CONTROLLER
 |-----------------------------------
 */

 Router::add('GET', '/products/category', function (...$params) {
    // Esperamos que $params[0] contenga el ID de la categoría
    if (!empty($params) && is_numeric($params[0])) {
        (new ProductController())->listByCategory((int)$params[0]);
    } else {
        // Si no se pasó un parámetro válido, podrías redirigir o mostrar un error.
        error_log("Error: No se proporcionó el ID de categoría válido en /products/category");
        header('Location: ' . BASE_URL . 'products/list');
        exit;
    }
});

$categoriaController = new CategoriaController();

Router::add('GET', '/categoria/editar', function ($id) use ($categoriaController) {
    $categoriaController->edit((int)$id);
});

Router::add('POST', '/categoria/actualizar', function ($id) use ($categoriaController) {
    $categoriaController->update((int)$id);
});

Router::add('GET', '/categoria/crear', function () use ($categoriaController) {
    $categoriaController->mostrarFormularioCrear();
});

Router::add('POST', '/categoria/crear', function () use ($categoriaController) {
    $categoriaController->crear();
});

Router::add('GET', '/categoria/listar', function () use ($categoriaController) {
    $categoriaController->listar();
});

Router::add('POST', '/categoria/eliminar', function ($id) use ($categoriaController) {
    $categoriaController->eliminar((int)$id);
});

Router::add('GET', '/categoria/eliminar', function ($id) use ($categoriaController) {
    $categoriaController->eliminar((int)$id);
});


 // -----------------------------------
        // CARRITO
        // -----------------------------------

        // Ver el carrito
        Router::add('GET', '/carrito/view', function () {
            (new CarritoController())->index();
        });

        // Añadir producto:
        //   - /carrito/add/13  => añade 1 unidad
        //   - /carrito/add/13/2 => añade 2 unidades
        Router::add('GET', '/carrito/add', function (...$params) {
            $controller = new CarritoController();
            if (count($params) === 1) {
                // [productId]
                $controller->add((int)$params[0], 1);
            } elseif (count($params) === 2) {
                // [productId, quantity]
                $controller->add((int)$params[0], (int)$params[1]);
            } else {
                // Caso inesperado, no hay producto o hay demasiados parámetros
                $_SESSION['error_message'] = "Parámetros inválidos en carrito/add";
                header('Location: ' . BASE_URL . 'carrito/view');
                exit;
            }
        });

        // Disminuir producto: /carrito/decrease/13/1 => quita 1 unidad del producto 13
        Router::add('GET', '/carrito/decrease', function (...$params) {
            $controller = new CarritoController();
            if (count($params) === 2) {
                $controller->decrease((int)$params[0], (int)$params[1]);
            } else {
                $_SESSION['error_message'] = "Parámetros inválidos en carrito/decrease";
                header('Location: ' . BASE_URL . 'carrito/view');
                exit;
            }
        });

        // Quitar producto: /carrito/remove/13 => quita por completo el producto 13
        Router::add('GET', '/carrito/remove', function (...$params) {
            if (count($params) === 1) {
                (new CarritoController())->remove((int)$params[0]);
            } else {
                $_SESSION['error_message'] = "Parámetros inválidos en carrito/remove";
                header('Location: ' . BASE_URL . 'carrito/view');
                exit;
            }
        });

        // Vaciar el carrito por completo
        Router::add('GET', '/carrito/clear', function () {
            (new CarritoController())->clear();
        });




        /*
         |-----------------------------------
         | PRODUCT CONTROLLER
         |-----------------------------------
         */
        Router::add('GET', '/products/list', function () {
            (new ProductController())->list();
        });
        
        Router::add('GET', '/products/create', function () {
            (new ProductController())->create();
        });

        Router::add('POST', '/products/save', function () {
            (new ProductController())->save();
        });

        Router::add('POST', '/products/delete', function () {
            (new ProductController())->delete();
        });
        
        Router::add('POST', '/products/update', function () {
            (new ProductController())->update();
        });
        
        // Listado de productos por categoría
        Router::add('GET', '/products/category/(\d+)', function ($categoryId) {
            (new ProductController())->listByCategory((int)$categoryId);
        });

     


        Router::add('GET', '/products/edit', function () {
            (new ProductController())->edit();
        });
        

        // Finalmente, despachamos las rutas
        error_log("Checkpoint: Despachando rutas");
        Router::dispatch();
    }
}

// Función de utilidad para verificar roles de administrador
function requireAdmin() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: ' . BASE_URL);
        exit;
    }
}

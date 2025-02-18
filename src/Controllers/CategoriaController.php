<?php

namespace Controllers;

use Lib\Pages;
use Services\CategoriaService;
use Repositories\CategoriaRepository;
use Lib\Database;
use Lib\Utils;

class CategoriaController
{
    private Pages $pages;
    private CategoriaService $service;

    /**
     * Constructor de la clase CategoriaController.
     * Inicializa las dependencias necesarias.
     */
    public function __construct()
    {
        $this->pages = new Pages();

        // Obtener la conexión PDO desde Database
        $database = new Database();
        $pdo = $database->getConnection();

        // Pasar la conexión PDO al CategoriaRepository
        $categoriaRepository = new CategoriaRepository($pdo);

        // Crear el servicio de categoría con el repositorio
        $this->service = new CategoriaService($categoriaRepository);
    }

    /**
     * Método para mostrar la lista de categorías.
     */
    public function listar(): void
    {
        try {
            $categorias = $this->service->obtenerCategorias();
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Error al obtener las categorías.';
            $categorias = [];
        }

        $this->pages->render('categoria/listar', ['categorias' => $categorias]);
    }

    /**
     * Método para mostrar el formulario de creación de una categoría.
     */
    public function mostrarFormularioCrear(): void
    {
        $this->pages->render('categoria/crear');
    }

    /**
     * Método para crear una nueva categoría.
     */
    public function crear(): void
    {
        $nombre = $_POST['nombre'] ?? '';

        try {
            $this->service->crearCategoria($nombre);
            $_SESSION['success_message'] = 'Categoría creada exitosamente.';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Error al crear la categoría.';
        }

        header('Location: ' . BASE_URL . 'categoria/listar');
        exit;
    }

    /**
     * Método para editar una categoría existente.
     * Ahora recibe el ID como parámetro.
     */
    public function edit(int $id): void
    {
        error_log("Ejecutando edit con ID: " . $id);
        if (!Utils::isAdmin()) {
            $_SESSION['error_message'] = 'No tienes permisos para editar categorías.';
            header('Location: ' . BASE_URL . 'categoria/listar');
            exit;
        }

        if (!Utils::validateNumber($id, 1)) {
            $_SESSION['error_message'] = 'ID de categoría no válido.';
            header('Location: ' . BASE_URL . 'categoria/listar');
            exit;
        }

        try {
            $category = $this->service->obtenerCategoriaPorId($id);

            if (!$category) {
                $_SESSION['error_message'] = 'Categoría no encontrada.';
                header('Location: ' . BASE_URL . 'categoria/listar');
                exit;
            }

            $this->pages->render('categoria/edit', [
                'category' => $category,
            ]);
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Error al cargar la categoría: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'categoria/listar');
            exit;
        }
    }

    /**
     * Método para actualizar una categoría existente.
     * Ahora recibe el ID como parámetro.
     */
    public function update(int $id): void
    {
        if (!Utils::isAdmin()) {
            $_SESSION['error_message'] = 'No tienes permisos para actualizar categorías.';
            header('Location: ' . BASE_URL . 'categoria/listar');
            exit;
        }

        // Actualiza aquí: se espera que el formulario envíe 'nombre'
        $name = Utils::sanitizeText($_POST['nombre'] ?? '');

        if (!Utils::validateNumber($id, 1) || empty($name)) {
            $_SESSION['error_message'] = 'Datos no válidos.';
            header('Location: ' . BASE_URL . 'categoria/listar');
            exit;
        }

        try {
            $updated = $this->service->actualizarCategoria($id, $name);

            if ($updated) {
                $_SESSION['success_message'] = 'Categoría actualizada exitosamente.';
            } else {
                $_SESSION['error_message'] = 'No se pudo actualizar la categoría.';
            }
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Error al actualizar la categoría: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . 'categoria/listar');
        exit;
    }

    /**
     * Método para eliminar una categoría existente.
     */
    public function eliminar(int $id): void
    {
        try {
            $this->service->eliminarCategoria($id);
            $_SESSION['success_message'] = 'Categoría eliminada exitosamente.';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Error al eliminar la categoría.';
        }

        header('Location: ' . BASE_URL . 'categoria/listar');
        exit;
    }
}

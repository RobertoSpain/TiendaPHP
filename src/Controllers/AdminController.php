<?php

namespace Controllers;

use Lib\Pages;
use Lib\Utils;
use Lib\Database;
use Models\User;
use Repositories\UserRepository;
use Services\UserService;

class AdminController
{
    private Pages $pages;
    private UserService $userService;

    /**
     * Constructor de la clase AdminController.
     * Inicializa las dependencias necesarias.
     */
    public function __construct()
    {
        $this->pages = new Pages();

        // Crear una instancia de Database
        $database = new Database(); // Aquí se instancia correctamente la clase Database

        // Pasar la instancia de Database al UserRepository
        $userRepository = new UserRepository($database);

        // Crear el servicio de usuario con el repositorio
        $this->userService = new UserService($userRepository);
    }

    /**
     * Método para mostrar la lista de usuarios en el panel de administración.
     * Solo accesible para administradores.
     */
    public function index(): void
    {
        $this->verificarAdministrador();

        try {
            $usuarios = $this->userService->findAll();
            $this->pages->render('admin/usuarios', ['usuarios' => $usuarios]);
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Error al obtener la lista de usuarios: ' . $e->getMessage();
            header('Location: ' . BASE_URL);
            exit;
        }
    }

    /**
     * Método para crear un nuevo usuario en la base de datos.
     * Solo accesible mediante una solicitud POST por administradores.
     */
    public function createUser(): void
    {
        $this->verificarAdministrador();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST['data'] ?? null;

            if ($data) {
                // Sanitizar y validar entradas
                $nombre = Utils::sanitizeText($data['nombre'] ?? '');
                $apellidos = Utils::sanitizeText($data['apellidos'] ?? '');
                $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
                $password = htmlspecialchars(trim($data['password'] ?? ''));
                $role = htmlspecialchars(trim($data['role'] ?? 'user'));

                // Validaciones de datos
                $errors = [];

                if (empty($nombre)) {
                    $errors[] = 'El nombre es obligatorio.';
                }
                if (empty($apellidos)) {
                    $errors[] = 'Los apellidos son obligatorios.';
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'El correo electrónico no es válido.';
                }
                if (strlen($password) < 6) {
                    $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
                }
                if (!in_array($role, ['user', 'admin'])) {
                    $errors[] = 'El rol seleccionado no es válido.';
                }

                // Si hay errores, mostrar mensaje y redirigir
                if (!empty($errors)) {
                    $_SESSION['error_message'] = implode('<br>', $errors);
                    header('Location: ' . BASE_URL . 'admin/usuarios');
                    exit;
                }

                // Crear instancia del usuario
                $user = new User(
                    null,
                    $nombre,
                    $apellidos,
                    $email,
                    password_hash($password, PASSWORD_BCRYPT),
                    $role
                );

                try {
                    // Guardar el usuario en la base de datos
                    $this->userService->save($user);
                    $_SESSION['success_message'] = 'Usuario creado exitosamente.';
                } catch (\PDOException $e) {
                    if (str_contains($e->getMessage(), 'Duplicate entry')) {
                        $_SESSION['error_message'] = 'El correo ya está registrado.';
                    } else {
                        $_SESSION['error_message'] = 'Error al crear el usuario: ' . $e->getMessage();
                    }
                }
            } else {
                $_SESSION['error_message'] = 'Los datos proporcionados no son válidos.';
            }

            // Redirigir a la vista de usuarios tras procesar el formulario.
            header('Location: ' . BASE_URL . 'admin/usuarios');
            exit;
        }
    }

    /**
     * Método para eliminar un usuario por su ID.
     * Solo accesible para administradores.
     *
     * @param int $id ID del usuario a eliminar.
     */
    public function eliminarUsuario(int $id): void
    {
        $this->verificarAdministrador();

        if (!Utils::validateNumber($id, 1)) {
            $_SESSION['error_message'] = 'ID de usuario no válido.';
            header('Location: ' . BASE_URL . 'admin/usuarios');
            exit;
        }

        try {
            // Intentar eliminar el usuario de la base de datos.
            $this->userService->delete($id);
            $_SESSION['success_message'] = 'Usuario eliminado exitosamente.';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Error al eliminar el usuario: ' . $e->getMessage();
        }

        // Redirigir a la lista de usuarios después de eliminar.
        header('Location: ' . BASE_URL . 'admin/usuarios');
        exit;
    }

    /**
     * Método privado para verificar si el usuario es administrador.
     * Si no tiene permisos, lo redirige a la página principal con un mensaje de error.
     */
    private function verificarAdministrador(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error_message'] = 'No tienes permisos para acceder a esta sección.';
            header('Location: ' . BASE_URL);
            exit;
        }
    }
}

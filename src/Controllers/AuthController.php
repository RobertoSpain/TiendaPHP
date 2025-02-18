<?php

namespace Controllers;

use Lib\Pages;
use Models\User;
use Services\UserService;
use Repositories\UserRepository;
use Lib\Database;

class AuthController
{
    private UserService $userService;
    private Pages $pages;
    private UserRepository $userRepository;

    /**
     * Constructor de AuthController.
     * Configura UserService utilizando Database y UserRepository.
     */
    public function __construct()
    {
        error_log("Checkpoint: Entrando al constructor de AuthController");

        // Crear una instancia de Database
        $database = new Database(); // Aquí se instancia correctamente la clase Database

        // Pasar la instancia de Database al UserRepository
        $this->userRepository = new UserRepository($database); 

        // Pasar el UserRepository al UserService
        $this->userService = new UserService($this->userRepository);
 
        // Instancia Pages para renderizar vistas
        $this->pages = new Pages();
    }

    /**
     * Método que muestra la página de inicio.
     */
    public function index()
    {
        log("Checkpoint: Entrando al método index");
        $this->pages->render('inicio');
    }

    /**
     * Método que muestra la vista de login.
     */
    public function login()
    {
        error_log("Checkpoint: Entrando al método login");
        $this->pages->render('Auth/login');
    }

    /**
     * Método para registrar un nuevo usuario.
     * Si la petición es POST, procesa los datos y los almacena en la base de datos.
     */
    public function register()
    {
        error_log("Checkpoint: Entrando al método register");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Checkpoint: Método POST recibido en register");

            $userData = $_POST['data'] ?? null;

            if (!empty($userData)) {
                try {
                    // Crear un nuevo objeto User a partir de los datos recibidos
                    $usuario = User::fromArray($userData);

                    // Forzar el rol a 'user'
                    $usuario->setRol('user');

                    // Validar los datos del usuario
                    if (!$usuario->validar()) {
                        $_SESSION['register'] = 'fail';
                        $_SESSION['errors'] = $usuario->getErrors();
                        error_log("Errores de validación: " . implode(", ", $usuario->getErrors()));
                    } else {
                        // Encriptar la contraseña antes de almacenarla
                        $usuario->setPassword(password_hash($usuario->getPassword(), PASSWORD_BCRYPT, ['cost' => 10]));

                        // Registrar el usuario en la base de datos
                        $this->userService->registerUser($usuario);
                        $_SESSION['register'] = 'success';
                        error_log("Checkpoint: Usuario registrado exitosamente");
                    }
                } catch (\Exception $e) {
                    $_SESSION['register'] = 'fail';
                    $_SESSION['error'] = $e->getMessage();
                    error_log("Error en register: " . $e->getMessage());
                }
            } else {
                $_SESSION['register'] = 'fail';
                error_log("Checkpoint: Datos POST no válidos en register");
            }
        }

        $this->pages->render('Auth/registerForm');
    }

    /**
     * Método para procesar el login del usuario.
     * Si la petición es POST, valida el email y la contraseña.
     */
    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitizar entradas
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = htmlspecialchars(trim($_POST['password'] ?? ''));

            error_log("Checkpoint: Recibido método POST en processLogin");
            error_log("Email recibido: $email");

            try {
                // Usar Database para la conexión
                $db = new Database();
                $pdo = $db->getConnection();

                // Preparar la consulta
                $stmt = $pdo->prepare('SELECT id, nombre, apellidos, email, password, rol FROM usuarios WHERE email = :email LIMIT 1');
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                $user = $stmt->fetch(\PDO::FETCH_ASSOC);
                error_log("Resultado de consulta: " . print_r($user, true));

                // Verificar si el usuario existe y la contraseña es correcta
                if (!$user || !password_verify($password, $user['password'])) {
                    $_SESSION['login_error'] = 'Correo o contraseña incorrectos.';
                    error_log("Error: Login fallido para email $email. Usuario encontrado: " . ($user ? 'Sí' : 'No'));
                    header('Location: ' . BASE_URL . 'login');
                    exit;
                }

                // Guardar los datos del usuario en la sesión
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                    'apellidos' => $user['apellidos'],
                    'email' => $user['email'],
                    'role' => $user['rol']
                ];
                error_log("Datos de sesión guardados: " . print_r($_SESSION['user'], true));

                // Redirigir al usuario según su rol
                if ($user['rol'] === 'admin') {
                    error_log("Redirigiendo al panel de administración...");
                    header('Location: ' . BASE_URL . 'admin');
                } else {
                    error_log("Redirigiendo a la página principal...");
                    header('Location: ' . BASE_URL);
                }
                exit;
            } catch (\PDOException $e) {
                error_log("Error en el login: " . $e->getMessage());
                $_SESSION['login_error'] = 'Error al conectar con la base de datos.';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
        }
    }
}


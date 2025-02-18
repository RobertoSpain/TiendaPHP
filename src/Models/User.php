<?php
namespace Models;

class User {

    protected static array $errores = [];

    /* 
     * Constructor de la clase User.
     * 
     * @param int|null $id ID del usuario (opcional).
     * @param string $nombre Nombre del usuario.
     * @param string $apellidos Apellidos del usuario.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     * @param string $rol Rol del usuario (ejemplo: 'admin' o 'user').
     */
    public function __construct(
        private ?int $id = null,
        private string $nombre = '',
        private string $apellidos = '',
        private string $email = '',
        private string $password = '',
        private string $rol = ''
    ) {}

    /* 
     * Obtener el ID del usuario.
     * 
     * @return int|null ID del usuario.
     */
    public function getId(): ?int {
        return $this->id;
    }

    /* 
     * Obtener el nombre del usuario.
     * 
     * @return string Nombre del usuario.
     */
    public function getNombre(): string {
        return $this->nombre;
    }

    /* 
     * Obtener los apellidos del usuario.
     * 
     * @return string Apellidos del usuario.
     */
    public function getApellidos(): string {
        return $this->apellidos;
    }

    /* 
     * Obtener el correo electrónico del usuario.
     * 
     * @return string Correo electrónico del usuario.
     */
    public function getEmail(): string {
        return $this->email;
    }

    /* 
     * Obtener la contraseña del usuario.
     * 
     * @return string Contraseña del usuario.
     */
    public function getPassword(): string {
        return $this->password;
    }

    /* 
     * Obtener el rol del usuario.
     * 
     * @return string Rol del usuario.
     */
    public function getRol(): string {
        return $this->rol;
    }

    /* 
     * Obtener la lista de errores de validación.
     * 
     * @return array Errores de validación.
     */
    public static function getErrores(): array {
        return self::$errores;
    }

    /* 
     * Obtener los errores específicos del usuario.
     * 
     * @return array Lista de errores.
     */
    public function getErrors(): array {
        return self::$errores;
    }

    /* 
     * Establecer el ID del usuario.
     * 
     * @param int|null $id ID del usuario.
     */
    public function setId(?int $id): void {
        $this->id = $id;
    }

    /* 
     * Establecer el nombre del usuario.
     * 
     * @param string $nombre Nombre del usuario.
     */
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    /* 
     * Establecer los apellidos del usuario.
     * 
     * @param string $apellidos Apellidos del usuario.
     */
    public function setApellidos(string $apellidos): void {
        $this->apellidos = $apellidos;
    }

    /* 
     * Establecer el correo electrónico del usuario.
     * 
     * @param string $email Correo electrónico del usuario.
     */
    public function setEmail(string $email): void {
        $this->email = $email;
    }

    /* 
     * Establecer la contraseña del usuario.
     * 
     * @param string $password Contraseña del usuario.
     */
    public function setPassword(string $password): void {
        $this->password = $password;
    }

    /* 
     * Establecer el rol del usuario.
     * 
     * @param string $rol Rol del usuario.
     */
    public function setRol(string $rol): void {
        $this->rol = $rol;
    }

    /* 
     * Establecer la lista de errores de validación.
     * 
     * @param array $errores Lista de errores.
     */
    public static function setErrores(array $errores): void {
        self::$errores = $errores;
    }

    /* 
     * Validar los datos del usuario.
     * 
     * @return bool Devuelve true si la validación es correcta, false si hay errores.
     */
    public function validar(): bool {
        self::$errores = [];

        if (empty($this->nombre)) {
            self::$errores[] = "El nombre es obligatorio.";
        }

        if (empty($this->email)) {
            self::$errores[] = "El correo electrónico es obligatorio.";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores[] = "El formato del correo electrónico no es válido.";
        }

        if (empty($this->password)) {
            self::$errores[] = "La contraseña es obligatoria.";
        }

        return empty(self::$errores);
    }

    /* 
     * Sanitizar los datos del usuario.
     */
    public function sanitize(): void {
        $this->nombre = htmlspecialchars($this->nombre);
        $this->apellidos = htmlspecialchars($this->apellidos);
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
    }

    /* 
     * Crear una instancia de User a partir de un array.
     * 
     * @param array $data Datos del usuario en formato array.
     * @return User Instancia de la clase User.
     */
    public static function fromArray(array $data): User {
        $user = new User(
            id: $data['id'] ?? null,
            nombre: $data['nombre'] ?? '',
            apellidos: $data['apellidos'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            rol: $data['rol'] ?? ''
        );
    
        $user->sanitize();
        return $user;
    }

    /* 
     * Convertir el objeto User a un array.
     * 
     * @return array Datos del usuario en formato array.
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'email' => $this->email,
            'password' => $this->password,
            'rol' => $this->rol,
        ];
    }
}
?>

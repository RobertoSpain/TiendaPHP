<?php

namespace Services;

use Models\User;
use Repositories\UserRepository;
use Lib\Utils;

class UserService {
    private UserRepository $userRepository;

    /** 
     * Constructor de la clase UserService.
     * Inicializa el repositorio de usuarios.
     *
     * @param UserRepository $userRepository Instancia del repositorio de usuarios.
     */
    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /** 
     * Método para registrar un nuevo usuario.
     * 
     * @param User $user Instancia del usuario a registrar.
     * @return bool Devuelve true si el registro fue exitoso.
     * @throws \Exception Si los datos no son válidos o el correo electrónico ya está registrado.
     */
    public function registerUser(User $user): bool {
        // Validar los datos del usuario
        $this->validateUserData($user);

        // Comprobar si el usuario ya existe por email
        if ($this->userRepository->findByEmail($user->getEmail()) !== null) {
            throw new \Exception('El correo electrónico ya está registrado.');
        }

        // Encriptar la contraseña
        $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));

        // Guardar el usuario
        $this->userRepository->save($user);
        return true;
    }

    /** 
     * Método para verificar las credenciales de un usuario.
     * 
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     * @return User|null Devuelve el usuario si las credenciales son válidas, o null si no lo son.
     */
    public function loginUser(string $email, string $password): ?User {
        // Validar el correo electrónico
        if (!Utils::validateEmail($email)) {
            throw new \Exception('El correo electrónico no es válido.');
        }

        $user = $this->userRepository->findByEmail($email);

        // Verificar si el usuario existe y si la contraseña es válida
        if ($user === null || !password_verify($password, $user->getPassword())) {
            throw new \Exception('Las credenciales no son válidas.');
        }

        return $user;
    }

    /** 
     * Método para buscar un usuario por su ID.
     * 
     * @param int $id ID del usuario a buscar.
     * @return User|null Devuelve el usuario si existe, o null si no existe.
     */
    public function findUserById(int $id): ?User {
        if (!Utils::validateNumber($id, 1)) {
            throw new \Exception('El ID del usuario no es válido.');
        }

        return $this->userRepository->findById($id);
    }

    /** 
     * Método para actualizar la información de un usuario.
     * 
     * @param User $user Instancia del usuario con los datos actualizados.
     * @return bool Devuelve true si la actualización fue exitosa.
     * @throws \Exception Si los datos no son válidos o el usuario no existe.
     */
    public function updateUser(User $user): bool {
        // Validar los datos del usuario
        $this->validateUserData($user);

        // Verificar si el usuario existe
        if ($this->userRepository->findById($user->getId()) === null) {
            throw new \Exception('El usuario no existe.');
        }

        // Preparar los datos para la actualización
        $data = [
            'nombre' => $user->getNombre(),
            'apellidos' => $user->getApellidos(),
            'email' => $user->getEmail(),
            'rol' => $user->getRol(),
            'password' => $user->getPassword() ? password_hash($user->getPassword(), PASSWORD_BCRYPT) : null,
        ];

        // Actualizar el usuario
        return $this->userRepository->update($user->getId(), $data);
    }

    /** 
     * Método para eliminar un usuario por su ID.
     * 
     * @param int $id ID del usuario a eliminar.
     * @return bool Devuelve true si la eliminación fue exitosa.
     * @throws \Exception Si el usuario no existe o el ID es inválido.
     */
    public function deleteUser(int $id): bool {
        // Validar el ID
        if (!Utils::validateNumber($id, 1)) {
            throw new \Exception('El ID del usuario no es válido.');
        }

        // Verificar si el usuario existe
        if ($this->userRepository->findById($id) === null) {
            throw new \Exception('El usuario no existe.');
        }

        // Eliminar el usuario
        $this->userRepository->delete($id);
        return true;
    }

    /**
     * Eliminar un usuario por su ID.
     *
     * @param int $id ID del usuario a eliminar.
     * @return bool Devuelve true si la eliminación fue exitosa.
     * @throws \Exception Si el usuario no existe o el ID es inválido.
     */
    public function delete(int $id): bool {
        // Validar el ID
        if (!Utils::validateNumber($id, 1)) {
            throw new \Exception('El ID del usuario no es válido.');
        }

        // Verificar si el usuario existe
        if ($this->userRepository->findById($id) === null) {
            throw new \Exception('El usuario no existe.');
        }

        // Eliminar el usuario
        return $this->userRepository->delete($id);
    }

    /** 
     * Método para validar los datos de un usuario.
     * 
     * @param User $user Instancia del usuario a validar.
     * @throws \Exception Si alguno de los datos no es válido.
     */
    private function validateUserData(User $user): void {
        if (!Utils::validateText($user->getNombre(), 50)) {
            throw new \Exception('El nombre del usuario no es válido.');
        }
        if (!Utils::validateText($user->getApellidos(), 100)) {
            throw new \Exception('Los apellidos del usuario no son válidos.');
        }
        if (!Utils::validateEmail($user->getEmail())) {
            throw new \Exception('El correo electrónico no es válido.');
        }
        if (!Utils::validateText($user->getRol(), 20)) {
            throw new \Exception('El rol del usuario no es válido.');
        }
    }

    /**
     * Obtener todos los usuarios.
     *
     * @return array Devuelve un array de objetos User con los datos de todos los usuarios.
     * @throws \Exception Si ocurre un error al obtener los usuarios.
     */
    public function findAll(): array {
        return $this->userRepository->findAll();
    }

    /**
     * Guardar un usuario en la base de datos.
     *
     * @param User $user Objeto de tipo User con los datos a guardar.
     * @return bool Devuelve true si el guardado fue exitoso.
     * @throws \Exception Si ocurre un error al guardar el usuario.
     */
    public function save(User $user): bool {
        return $this->userRepository->save($user);
    }
}

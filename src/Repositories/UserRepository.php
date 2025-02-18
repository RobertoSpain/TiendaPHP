<?php

namespace Repositories;

use Models\User;
use Lib\Database;
use PDOException;

class UserRepository {
    private Database $db;

    /**
     * Constructor de la clase UserRepository.
     * Recibe una instancia de Database para inicializar la conexión.
     *
     * @param Database $db Instancia de Database.
     */
    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Guardar un usuario en la base de datos.
     *
     * @param User $user Objeto de tipo User con los datos a guardar.
     * @return bool Devuelve true si el guardado fue exitoso.
     * @throws \Exception Si ocurre un error al guardar el usuario.
     */
    public function save(User $user): bool {
        try {
            // Validar los datos del usuario antes de guardar
            if (empty($user->getNombre()) || empty($user->getEmail()) || empty($user->getPassword())) {
                throw new \InvalidArgumentException('Faltan datos obligatorios para el usuario.');
            }

            $stmt = $this->db->getConnection()->prepare('
                INSERT INTO usuarios (nombre, apellidos, email, password, rol) 
                VALUES (:nombre, :apellidos, :email, :password, :rol)
            ');
            $stmt->bindValue(':nombre', $user->getNombre());
            $stmt->bindValue(':apellidos', $user->getApellidos());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':password', $user->getPassword());
            $stmt->bindValue(':rol', $user->getRol());
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new \Exception('Error al guardar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Obtener todos los usuarios de la base de datos.
     *
     * @return array Devuelve un array de objetos User con los datos de todos los usuarios.
     * @throws \Exception Si ocurre un error al obtener los usuarios.
     */
    public function findAll(): array {
        try {
            $stmt = $this->db->getConnection()->query('SELECT * FROM usuarios');
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return array_map(fn($data) => User::fromArray($data), $result);
        } catch (PDOException $e) {
            throw new \Exception('Error al obtener usuarios: ' . $e->getMessage());
        }
    }

    /**
     * Buscar un usuario por su ID.
     *
     * @param int $id ID del usuario a buscar.
     * @return User|null Devuelve un objeto User si se encuentra, o null si no existe.
     * @throws \Exception Si ocurre un error al buscar el usuario.
     */
    public function findById(int $id): ?User {
        try {
            $stmt = $this->db->getConnection()->prepare('SELECT * FROM usuarios WHERE id = :id');
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $data ? User::fromArray($data) : null;
        } catch (PDOException $e) {
            throw new \Exception('Error al buscar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Buscar un usuario por su correo electrónico.
     *
     * @param string $email Correo electrónico del usuario a buscar.
     * @return User|null Devuelve un objeto User si se encuentra, o null si no existe.
     * @throws \Exception Si ocurre un error al buscar el usuario por email.
     */
    public function findByEmail(string $email): ?User {
        try {
            $stmt = $this->db->getConnection()->prepare('SELECT * FROM usuarios WHERE email = :email');
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $data ? User::fromArray($data) : null;
        } catch (PDOException $e) {
            throw new \Exception('Error al buscar el usuario por email: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar un usuario en la base de datos.
     *
     * @param int $id ID del usuario a actualizar.
     * @param array $data Datos actualizados del usuario.
     * @return bool Devuelve true si la actualización fue exitosa.
     * @throws \Exception Si ocurre un error al actualizar el usuario.
     */
    public function update(int $id, array $data): bool {
        try {
            $query = "UPDATE usuarios 
                      SET nombre = :nombre, 
                          apellidos = :apellidos, 
                          email = :email, 
                          password = :password, 
                          rol = :rol 
                      WHERE id = :id";
            
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->bindValue(':nombre', $data['nombre'] ?? null, \PDO::PARAM_STR);
            $stmt->bindValue(':apellidos', $data['apellidos'] ?? null, \PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'] ?? null, \PDO::PARAM_STR);
            $stmt->bindValue(':password', $data['password'] ?? null, \PDO::PARAM_STR);
            $stmt->bindValue(':rol', $data['rol'] ?? null, \PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new \Exception('Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un usuario de la base de datos.
     *
     * @param int $id ID del usuario a eliminar.
     * @return bool Devuelve true si la eliminación fue exitosa.
     * @throws \Exception Si ocurre un error al eliminar el usuario.
     */
    public function delete(int $id): bool {
        try {
            $stmt = $this->db->getConnection()->prepare('DELETE FROM usuarios WHERE id = :id');
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new \Exception('Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}

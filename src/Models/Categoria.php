<?php

namespace Models;

class Categoria
{
    private ?int $id;
    private string $nombre;

    /**
     * Constructor de la clase Categoria.
     *
     * @param int|null $id ID de la categoría (opcional).
     * @param string $nombre Nombre de la categoría.
     */
    public function __construct(?int $id = null, string $nombre = '')
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    /**
     * Obtener el ID de la categoría.
     *
     * @return int|null ID de la categoría.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtener el nombre de la categoría.
     *
     * @return string Nombre de la categoría.
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Establecer el ID de la categoría.
     *
     * @param int|null $id ID de la categoría.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Establecer el nombre de la categoría.
     *
     * @param string $nombre Nombre de la categoría.
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * Validar si el nombre de la categoría es válido.
     *
     * @return bool Devuelve true si el nombre no está vacío, false en caso contrario.
     */
    public function validar(): bool
    {
        return !empty($this->nombre);
    }

    /**
     * Crear una instancia de Categoria desde un array.
     *
     * @param array $data Datos de la categoría en forma de array.
     * @return self Devuelve una instancia de Categoria.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['nombre'] ?? ''
        );
    }

    /**
     * Convertir la categoría a un array.
     *
     * @return array Devuelve un array con los datos de la categoría.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
        ];
    }
}

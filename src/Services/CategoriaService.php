<?php 
namespace Services;

use Models\Categoria;
use Repositories\CategoriaRepository;
use Lib\Utils;

class CategoriaService {
    private CategoriaRepository $repository;

    /** 
     * Constructor de la clase CategoriaService.
     * Inicializa el repositorio de categorías.
     *
     * @param CategoriaRepository $repository Instancia del repositorio de categorías.
     */
    public function __construct(CategoriaRepository $repository) {
        $this->repository = $repository;
    }

    /** 
     * Método para crear una nueva categoría.
     */
    public function crearCategoria(string $nombre): void {
        $categoria = new Categoria(null, $nombre);

        if (!$categoria->validar()) {
            throw new \InvalidArgumentException('El nombre de la categoría no es válido.');
        }

        $this->repository->save($categoria);
    }

    /** 
     * Método para actualizar una categoría existente.
     */
    public function actualizarCategoria(int $id, string $name): bool {
        if (!Utils::validateNumber($id, 1)) {
            throw new \InvalidArgumentException('ID de categoría no válido.');
        }

        if (!Utils::validateText($name, 100)) {
            throw new \InvalidArgumentException('El nombre de la categoría no es válido.');
        }

        return $this->repository->update($id, $name);
    }

    /** 
     * Método para eliminar una categoría.
     */
    public function eliminarCategoria(int $id): void {
        if (!Utils::validateNumber($id, 1)) {
            throw new \InvalidArgumentException('ID de categoría no válido.');
        }

        $this->repository->delete($id);
    }

    /** 
     * Método para obtener todas las categorías.
     */
    public function obtenerCategorias(): array {
        return $this->repository->findAll();
    }

    /** 
     * Método para obtener una categoría por su ID.
     */
    public function obtenerCategoriaPorId(int $id): ?Categoria {
        if (!Utils::validateNumber($id, 1)) {
            throw new \InvalidArgumentException('ID de categoría no válido.');
        }

        return $this->repository->findById($id);
    }
}
?>
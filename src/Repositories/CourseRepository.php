<?php

namespace RevvoApi\Repositories;

use RevvoApi\Database\Database;
use PDO;
use PDOException;

class CourseRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function list(): array
    {
        try {
            $baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/';

            $stmt = $this->db->query("SELECT c.*, 
                (SELECT CONCAT('$baseUrl', image_url) FROM course_images ci WHERE ci.course_id = c.id ORDER BY ci.id ASC LIMIT 1) AS first_image 
                FROM courses c");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao listar cursos.', 'error' => $e->getMessage()];
        }
    }

    public function create(array $data): array
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO courses (title, description, link) VALUES (:title, :description, :link)");
            $stmt->execute([
                'title' => $data['title'],
                'description' => $data['description'],
                'link' => $data['link']
            ]);
            return ['success' => true, 'message' => 'Curso criado com sucesso.', 'id' => (int) $this->db->lastInsertId()];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao criar curso.', 'error' => $e->getMessage()];
        }
    }

    public function view(int $id): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);

            return $course ? ['success' => true, 'message' => 'Curso encontrado.', 'data' => $course]
                : ['success' => false, 'message' => 'Curso não encontrado.', 'data' => null];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao buscar curso.', 'error' => $e->getMessage()];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            $stmt = $this->db->prepare("UPDATE courses SET title = :title, description = :description, link = :link WHERE id = :id");
            $stmt->execute([
                'id' => $id,
                'title' => $data['title'],
                'description' => $data['description'],
                'link' => $data['link']
            ]);

            return ['success' => true, 'message' => 'Curso atualizado com sucesso.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao atualizar curso.', 'error' => $e->getMessage()];
        }
    }

    public function delete(int $id): array
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM courses WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return ['success' => true, 'message' => 'Curso excluído com sucesso.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao excluir curso.', 'error' => $e->getMessage()];
        }
    }
}

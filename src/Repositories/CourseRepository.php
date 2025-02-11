<?php

namespace RevvoApi\Repositories;

use RevvoApi\Database\Database;
use PDO;

class CourseRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function list(): array
    {
        $stmt = $this->db->query("SELECT c.*, 
            (SELECT image_url FROM course_images ci WHERE ci.course_id = c.id ORDER BY ci.id ASC LIMIT 1) AS first_image 
            FROM courses c");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO courses (title, description, link) VALUES (:title, :description, :link)");
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'link' => $data['link']
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function view(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        return $course ?: null;
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("UPDATE courses SET title = :title, description = :description, link = :link WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'],
            'link' => $data['link']
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

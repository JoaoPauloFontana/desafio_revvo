<?php

namespace RevvoApi\Repositories;

use RevvoApi\Database\Database;
use PDO;

class CourseImagesRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function saveImage(int $courseId, string $imagePath): void
    {
        $stmt = $this->db->prepare("INSERT INTO course_images (course_id, image_url) VALUES (:course_id, :image_url)");
        $stmt->execute([
            'course_id' => $courseId,
            'image_url' => $imagePath
        ]);
    }

    public function getImagesByCourseId(int $courseId): array
    {
        $stmt = $this->db->prepare("SELECT image_url FROM course_images WHERE course_id = :course_id");
        $stmt->execute(['course_id' => $courseId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function deleteImagesByCourseId(int $courseId): void
    {
        $stmt = $this->db->prepare("DELETE FROM course_images WHERE course_id = :course_id");
        $stmt->execute(['course_id' => $courseId]);
    }
}

<?php

namespace RevvoApi\Services;

use RevvoApi\Repositories\CourseRepository;
use RevvoApi\Repositories\CourseImagesRepository;

class CourseService
{
    private CourseRepository $courseRepository;
    private CourseImagesRepository $courseImagesRepository;

    public function __construct()
    {
        $this->courseRepository = new CourseRepository();
        $this->courseImagesRepository = new CourseImagesRepository();
    }

    public function list(): array
    {
        $courses = $this->courseRepository->list();
        if (empty($courses)) {
            return ['success' => false, 'message' => 'Nenhum curso encontrado.', 'data' => []];
        }
        return ['success' => true, 'message' => 'Lista de cursos recuperada com sucesso.', 'data' => $courses];
    }

    public function create(array $data): array
    {
        $courseId = $this->courseRepository->create($data);
        if (!$courseId) {
            return ['success' => false, 'message' => 'Erro ao criar o curso.'];
        }

        if (!empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $imageBase64) {
                $this->saveImage($courseId, $imageBase64);
            }
        }

        return ['success' => true, 'message' => 'Curso criado com sucesso.', 'data' => ['id' => $courseId]];
    }

    public function view(int $id): array
    {
        $course = $this->courseRepository->view($id);
        if (empty($course)) {
            return ['success' => false, 'message' => 'Curso não encontrado.', 'data' => null];
        }
        $course['images'] = $this->courseImagesRepository->getImagesByCourseId($id);
        return ['success' => true, 'message' => 'Curso encontrado.', 'data' => $course];
    }

    public function update(int $id, array $data): array
    {
        $updated = $this->courseRepository->update($id, $data);
        if (!$updated) {
            return ['success' => false, 'message' => 'Erro ao atualizar o curso.'];
        }

        if (!empty($data['images']) && is_array($data['images'])) {
            $this->courseImagesRepository->deleteImagesByCourseId($id);
            foreach ($data['images'] as $imageBase64) {
                $this->saveImage($id, $imageBase64);
            }
        }

        return ['success' => true, 'message' => 'Curso atualizado com sucesso.'];
    }

    public function delete(int $id): array
    {
        $deleted = $this->courseRepository->delete($id);
        if (!$deleted) {
            return ['success' => false, 'message' => 'Erro ao excluir o curso.'];
        }
        $this->courseImagesRepository->deleteImagesByCourseId($id);
        return ['success' => true, 'message' => 'Curso excluído com sucesso.'];
    }

    private function saveImage(int $courseId, string $imageBase64): void
    {
        $imageData = explode(',', $imageBase64);
        if (count($imageData) !== 2) {
            return;
        }

        $imageInfo = explode(';', $imageData[0]);
        $mimeType = str_replace('data:', '', $imageInfo[0]);
        $extension = explode('/', $mimeType)[1] ?? 'png';

        $imageDecoded = base64_decode($imageData[1]);
        if (!$imageDecoded) {
            return;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imagePath = $uploadDir . uniqid('course_', true) . '.' . $extension;
        file_put_contents($imagePath, $imageDecoded);

        $baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/';

        $relativePath = $baseUrl . 'uploads/' . basename($imagePath);
        $this->courseImagesRepository->saveImage($courseId, $relativePath);
    }
}

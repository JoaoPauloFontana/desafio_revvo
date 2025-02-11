<?php

namespace RevvoApi\Controllers;

use RevvoApi\Services\CourseService;
use RevvoApi\Utils\Validator;

class CourseController
{
    private CourseService $courseService;

    public function __construct()
    {
        $this->courseService = new CourseService();
    }

    public function list(): string
    {
        $response = $this->courseService->list();
        http_response_code($response['success'] ? 200 : 500);
        return json_encode($response, JSON_THROW_ON_ERROR);
    }

    public function create(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'link' => 'required|string',
        ];

        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            http_response_code(400);
            return json_encode(['success' => false, 'message' => 'Erro de validação', 'errors' => $errors], JSON_THROW_ON_ERROR);
        }

        $response = $this->courseService->create($data);
        http_response_code($response['success'] ? 201 : 500);
        return json_encode($response, JSON_THROW_ON_ERROR);
    }

    public function view(int $id): string
    {
        $response = $this->courseService->view($id);
        http_response_code($response['success'] ? 200 : 404);
        return json_encode($response, JSON_THROW_ON_ERROR);
    }

    public function update(int $id): string
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'link' => 'required|string',
        ];

        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            http_response_code(400);
            return json_encode(['success' => false, 'message' => 'Erro de validação', 'errors' => $errors], JSON_THROW_ON_ERROR);
        }

        $response = $this->courseService->update($id, $data);
        http_response_code($response['success'] ? 200 : 500);
        return json_encode($response, JSON_THROW_ON_ERROR);
    }

    public function delete(int $id): string
    {
        $response = $this->courseService->delete($id);
        http_response_code($response['success'] ? 200 : 500);
        return json_encode($response, JSON_THROW_ON_ERROR);
    }
}

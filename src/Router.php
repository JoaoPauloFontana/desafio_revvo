<?php

namespace RevvoApi;

use RevvoApi\Controllers\CourseController;

class Router
{
    public function handle(): string
    {
        $uri = trim((string) $_SERVER['REQUEST_URI'], '/');
        $method = $_SERVER['REQUEST_METHOD'];

        $courseController = new CourseController();

        switch ([$method, $uri]) {
            case ['GET', 'courses']:
                return $courseController->list();
            case ['POST', 'courses']:
                return $courseController->create();
            case ['GET', preg_match('/courses\/([0-9]+)/', $uri, $matches) ? $uri : false]:
                return $courseController->view($matches[1]);
            case ['PUT', preg_match('/courses\/([0-9]+)/', $uri, $matches) ? $uri : false]:
                return $courseController->update($matches[1]);
            case ['DELETE', preg_match('/courses\/([0-9]+)/', $uri, $matches) ? $uri : false]:
                return $courseController->delete($matches[1]);
            default:
                http_response_code(404);
                return json_encode(['error' => 'Route not found'], JSON_THROW_ON_ERROR);
        }
    }
}
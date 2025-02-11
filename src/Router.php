<?php

namespace RevvoApi;

class Router
{
    //TODO: Adicionar lógica de roteamento.
    //P.S: Adicionado apenas para fazer a configuração inicial do projeto
    public function handle(): string
    {
        $uri = trim((string) $_SERVER['REQUEST_URI'], '/');

        switch ($uri) {
            case 'courses':
                return json_encode(['message' => 'Lista de cursos'], JSON_THROW_ON_ERROR);
            case 'courses/create':
                return json_encode(['message' => 'Criar novo curso'], JSON_THROW_ON_ERROR);
            default:
                http_response_code(404);
                return json_encode(['error' => 'Route not found'], JSON_THROW_ON_ERROR);
        }
    }
}
# 📚 API de Cursos

Esta API permite a criação, listagem, atualização e remoção de cursos, além de gerenciar imagens associadas a cada curso.

## 🚀 Tecnologias Necessárias

Antes de começar, certifique-se de ter as seguintes tecnologias instaladas:

- **PHP 8.2+**
- **Composer** (para gerenciar dependências)
- **Docker e Docker Compose** (para rodar a aplicação)
- **PostgreSQL** (rodado via Docker)

---

## 📌 Configuração do Projeto

### 1️⃣ Clonar o Repositório
```sh
git clone https://github.com/JoaoPauloFontana/desafio_revvo.git
cd desafio_revvo
```

### 2️⃣ Subir os containers Docker
```sh
docker-compose up -d --build
```
Isso iniciará os serviços do PHP e do PostgreSQL.

### 3️⃣ Instalar dependências do PHP
```sh
composer install
```

### 4️⃣ Rodar as Migrations
```sh
php src/Database/migrations/migrate.php
```
Isso criará as tabelas no banco de dados.

---

## 📡 Rotas da API

### ➤ Listar todos os cursos
**Rota:** `GET /courses`
```sh
curl -X GET http://localhost:8080/courses
```
**Resposta:**
```json
{
    "success": true,
    "message": "Lista de cursos recuperada com sucesso.",
    "data": [
        {
            "id": 1,
            "title": "Curso de PHP",
            "description": "Aprenda PHP do zero",
            "link": "https://exemplo.com",
            "first_image": "http://localhost:8080/uploads/course_12345.png"
        }
    ]
}
```

### ➤ Criar um novo curso
**Rota:** `POST /courses`
```sh
curl -X POST http://localhost:8080/courses \
     -H "Content-Type: application/json" \
     -d '{
           "title": "Curso de PHP",
           "description": "Aprenda PHP do zero",
           "link": "https://exemplo.com",
           "images": [
               "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA..."
           ]
         }'
```
**Resposta:**
```json
{
    "success": true,
    "message": "Curso criado com sucesso.",
    "data": {"id": 1}
}
```

### ➤ Visualizar um curso específico
**Rota:** `GET /courses/{id}`
```sh
curl -X GET http://localhost:8080/courses/1
```
**Resposta:**
```json
{
    "success": true,
    "message": "Curso encontrado.",
    "data": {
        "id": 1,
        "title": "Curso de PHP",
        "description": "Aprenda PHP do zero",
        "link": "https://exemplo.com",
        "images": [
            "http://localhost:8080/uploads/course_12345.png"
        ]
    }
}
```

### ➤ Atualizar um curso
**Rota:** `PUT /courses/{id}`
```sh
curl -X PUT http://localhost:8080/courses/1 \
     -H "Content-Type: application/json" \
     -d '{
           "title": "Curso de PHP Avançado",
           "description": "Aprofunde seus conhecimentos em PHP",
           "link": "https://exemplo.com",
           "images": [
               "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA..."
           ]
         }'
```
**Resposta:**
```json
{
    "success": true,
    "message": "Curso atualizado com sucesso."
}
```

### ➤ Excluir um curso
**Rota:** `DELETE /courses/{id}`
```sh
curl -X DELETE http://localhost:8080/courses/1
```
**Resposta:**
```json
{
    "success": true,
    "message": "Curso excluído com sucesso."
}
```

---

## 🛠️ Estrutura do Projeto

```
/api-cursos
├── src/
│   ├── Controllers/
│   ├── Database/
│   ├── Repositories/
│   ├── Services/
│   ├── Utils/
│   ├── Router.php
├── public/
│   ├── uploads/  (armazenamento das imagens)
│   ├── .htaccess
|   ├── index.php 
├── docker-compose.yml
├── Dockerfile
├── README.md
└── composer.json
```

---

## 🔥 Considerações
- Todas as imagens são salvas dentro da pasta `public/uploads/`.
- O banco de dados PostgreSQL é inicializado via Docker.
- Todas as respostas seguem um **padrão JSON estruturado**.
- As migrations devem ser rodadas antes de iniciar o projeto.

Se houver dúvidas ou melhorias, sinta-se à vontade para contribuir! 🚀
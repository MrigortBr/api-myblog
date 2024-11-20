# MyBlog API
[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://github.com/MrigortBr/api-myblog/blob/main/LICENSE)![Build Passed](https://img.shields.io/badge/build-passing-brightgreen)
## Objetivo
Projeto passado pelo professor Emanuel Felix de Aguiar da cadeira de API com Laravel.

Os objetivos do projeto consistiam em:

1. Autenticação e Autorização:
o Utilizar Laravel Sanctum para proteger os endpoints.
o Controle de autorização para garantir que apenas o autor do post ou comentário possa
editá-lo ou excluí-lo.

2. Migrations e Estrutura de Banco de Dados:
o Tabela de posts: com campos id, title, content, user_id, category_id, published_at
(timestamp para data de publicação) e status (ex.: rascunho ou publicado).
o Tabela de comments: com campos id, post_id, user_id e content.
o Tabela de categories: com campos id e name, para categorizar os posts.
3. Models e Relacionamentos:
o Post: relação com Comment e Category.
o Comment: relação com Post e User.
o Category: relação com Post.
o User: relação com Post e Comment.
4. Endpoints:
o CRUD de Posts: endpoints para criação, leitura, atualização e exclusão de posts. Apenas o
autor pode editar ou excluir um post.
o Categorias: endpoints para listar e associar posts a categorias específicas.
o Comentários:
▪ CRUD de comentários. Apenas o autor pode editar ou excluir seus comentários.
▪ Endpoint para listar comentários de um post específico.

5. Funcionalidades Extras:
o Filtros e Paginação:
▪ Implementar filtros para posts por categoria, autor, status e data de criação.
▪ Paginação padrão para a listagem de posts e comentários, evitando sobrecarregar
o cliente com listas extensas.

o Ordenação: Opções para ordenar posts por data de criação e popularidade (número de
comentários).


## Instruções de Instalação e Execução

1. Clone o repositório: `git clone https://github.com/MrigortBr/api-myblog.git`
2. Configure o ambiente de desenvolvimento conforme necessário (Java, Maven, Docker, etc.).
3. Configure o banco de dados MySQL e atualize as configurações de conexão no arquivo `.env`.
4. Execute a aplicação Spring Boot usando o Maven: `php artisan serve`.
5. Acesse os endpoints da API utilizando o Postman ou outra ferramenta similar.
## Tecnologias Usadas

### Dependências

        "fruitcake/laravel-cors": "^2.0.5",
        "guzzlehttp/guzzle": "^7.2",
        "laravel/framework": "^9.0",
        "laravel/sanctum": "^3.3",
        "laravel/tinker": "^2.7"

Para mais detalhes das dependências veja o arquivo [package.json](https://github.com/MrigortBr/api-myblog/blob/development/package.json)

### Tecnologias

- **PHP**: Linguagem de programação principal.
- **Laravel**: FrameWork principal.
- **Composer**: Gerenciador de dependências e construção de projetos.
- **Docker**: Plataforma para desenvolvimento, envio e execução de aplicações em containers.
- **Mysql**: Sistema de gerenciamento de banco de dados.

### Ferramentas de Desenvolvimento

- **Postman**: Ferramenta para testar APIs.
- **Visual Studio code**: IDE baseada em Eclipse para desenvolvimento Spring.
- **DBeaver**: Ferramenta de administração de banco de dados universal e gratuita.

## Endpoints da API
Para utilizar de forma mais rapida todos os endpoints basta utilizar a [base](https://github.com/MrigortBr/api-myblog/blob/development/MyBlog.postman_collection.json) para o [postman](https://www.postman.com/downloads/).

A API expõe os seguintes endpoints:

### AuthController - Controle de autenticação

- **Register**
  - **Descrição:** Cria a conta
  - **Método HTTP:** POST
  - **Endpoint:** `http://localhost:8000/register`
  - **Exemplo de Corpo da Requisição (Obrigatorio):**
  
      ```json
        {
            "name": "Nome",
            "email": "Email@gmail.com",
            "password": "Senha"
        }
    ```

- **Login**
  - **Descrição:** Realiza o login da conta.
  - **Método HTTP:** POST
  - **Endpoint:** `http://localhost:8000/login`
  - **Exemplo de Corpo da Requisição (Obrigatorio):**

      ```json
        {
            "email": "Email@gmail.com",
            "password": "Senha"
        }
    ```

- **Logout**
  - **Descrição:** Invalida o token gerado para o login da conta.
  - **Método HTTP:** POST
  - **Endpoint:** `http://localhost:8000/logout`
  - **Exemplo de Header da Requisição (Obrigatorio):**

    ```json
    Bearer Token EXTOKEN
    ```

### Posts

- **Criar postagem**
  - **Descrição:** Adiciona uma postagem.
  - **Método HTTP:** POST
  - **Endpoint:** `http://localhost:8000/post`
  - **Exemplo de Corpo da Requisição (Obrigatório):**

      ```json
        {
            "title": "Titulo postagem",
            "content": "Conteudo postagem",
            "status": "published ou draft",
            "category": "categoria"
        }
      
  - **Exemplo de Header da Requisição:**

    ```json
    Bearer Token EXTOKEN
    ```

- **Listar postagem baseado no seu id**
  - **Descrição:** Retorna uma postagem específica pelo ID.
  - **Método HTTP:** GET
  - **Endpoint:** `http://localhost:8000/post/{id}`
  - **Exemplo de Header da Requisição (Obrigatorio):**

    ```json
    Bearer Token EXTOKEN
    ```

- **Listar postagens**
  - **Descrição:** Lista postagens, baseado na categoria da postagem ou de forma aleatoria.
  - **Método HTTP:** GET
  - **Endpoint:** `http://localhost:8000/posts`
  - **Exemplo de Header da Requisição (Obrigatorio):**
    ```json
    Bearer Token EXTOKEN
    ```
  

- **Listar postagens do usuario**
  - **Descrição:** Lista as postagem que o usuario criou.
  - **Método HTTP:** GET
  - **Endpoint:** `http://localhost:8000/myposts`
  - **Exemplo de Header da Requisição (Obrigatorio):**

    ```json
    Bearer Token EXTOKEN
    ```

- **Atualizar postagem usuario**
  - **Descrição:** Atualiza uma postagem do usuario.
  - **Método HTTP:** PUT
  - **Endpoint:** `http://localhost:8000/post/{id}`
  - **Exemplo de Corpo da Requisição (Obrigatorio pelo menos um campo):**

      ```json
        {
            "title": "Titulo postagem",
            "content": "Conteudo postagem",
            "status": "published ou draft",
            "category": "categoria"
        }
      ```
  - **Exemplo de Header da Requisição (Obrigatorio):**

    ```json
    Bearer Token EXTOKEN
    ```

- **Deletar postagem do usuario**
  - **Descrição:** Delata uma postagem do usuario.
  - **Método HTTP:** DELETE
  - **Endpoint:** `http://localhost:8000/post/{id}`
  - **Exemplo de Header da Requisição (Obrigatorio):**

    ```json
    Bearer Token EXTOKEN
    ```

### Comentarios

- **Criar comentario**
  - **Descrição:** Adiciona um comentario.
  - **Método HTTP:** POST
  - **Endpoint:** `http://localhost:8000/comment/{id}`
  - **Exemplo de Corpo da Requisição (Obrigatório):**

      ```json
        {
            "content": "Conteudo comentario",
        }
      
  - **Exemplo de Header da Requisição:**

    ```json
    Bearer Token EXTOKEN
    ```

- **Listar comentarios baseado no seu id**
  - **Descrição:** Retorna uma comentario específico pelo ID.
  - **Método HTTP:** GET
  - **Endpoint:** `http://localhost:8000/comment/{id}`
  - **Exemplo de Header da Requisição (Obrigatorio):**

    ```json
    Bearer Token EXTOKEN
    ```

- **Listar comentarios do usuario**
  - **Descrição:** Lista os comentarios que o usuario criou.
  - **Método HTTP:** GET
  - **Endpoint:** `http://localhost:8000/comments`
  - **Exemplo de Header da Requisição (Obrigatorio):**

    ```json
    Bearer Token EXTOKEN
    ```

- **Atualizar comentario do usuario**
  - **Descrição:** Atualiza um comentario do usuario.
  - **Método HTTP:** PUT
  - **Endpoint:** `http://localhost:8000/comment/{id}`
  - **Exemplo de Corpo da Requisição (Obrigatorio):**

      ```json
        {
            "content": "Conteudo comentario",
        }
      ```
  - **Exemplo de Header da Requisição (Obrigatorio):**

    ```json
    Bearer Token EXTOKEN
    ```

- **Deletar comentario do usuario**
  - **Descrição:** Deleta um comentario do usuario.
  - **Método HTTP:** DELETE
  - **Endpoint:** `http://localhost:8000/comment/{id}`
  - **Exemplo de Header da Requisição (Obrigatorio):**

    ```json
    Bearer Token EXTOKEN
    ```


## Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](https://github.com/MrigortBr/api-myblog/blob/main/LICENSE) para detalhes.

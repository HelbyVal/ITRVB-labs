<?php

namespace Helby\lessons\Controllers;

use Helby\lessons\Repositories\SQLiteArticlesRepository;
use Helby\lessons\Blog\Article;
use Ramsey\Uuid\Guid\Guid;
use Helby\lessons\Repositories\SQLiteUsersRepository;

class ArticlesController
{
    public SQLiteArticlesRepository $articlesRepository;
    public SQLiteUsersRepository $usersRepository;

    public function __construct($databasePath)
    {
        $this->articlesRepository = new SQLiteArticlesRepository($databasePath);
        $this->usersRepository = new SQLiteUsersRepository($databasePath);
    }

    public function addArticle()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $this->addArticleMethod($data);
    }

    public function addArticleMethod($data)
    {
        if (!isset($data['author_uuid'], $data['title'], $data['text'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. Required fields: author_uuid, title, text']);
            return 'Invalid input. Required fields: author_uuid, title, text';
        }

        $authorUuid = $data['author_uuid'];
        $title = $data['title'];
        $text = $data['text'];

        try {
            Guid::fromString($authorUuid);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. Required field: author_uuid']);
            return 'Invalid input. Required field: author_uuid';
        }

        try {
            $user = $this->usersRepository->get(Guid::fromString($authorUuid));
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return 'User not found';
        }

        try {
            $article = new Article(Guid::uuid4()->toString(), $authorUuid, $title, $text);

            $this->articlesRepository->save($article);
            http_response_code(201);
            echo json_encode([
                'message' => 'Article successfully created.',
                'article_id' => $article->getId(),
            ]);
            return $article->getId();
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            return $e->getMessage();
        }
    }

    public function getArticle()
    {
        if (!isset($_GET['article_uuid'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. Required field: article_uuid']);
            return 'Invalid input. Required field: article_uuid';
        }

        $articleUuid = $_GET['article_uuid'];

        try {
            $article = $this->articlesRepository->get(Guid::fromString($articleUuid));
            http_response_code(200);
            echo json_encode([
                'id' => $article->getId(),
                'author_id' => $article->getAuthorId(),
                'title' => $article->getTitle(),
                'text' => $article->getText(),
            ]);
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

<?php

namespace Helby\lessons\Controllers;

use Helby\lessons\Repositories\SQLiteCommentsRepository;
use Helby\lessons\Repositories\SQLiteArticlesRepository;
use Helby\lessons\Blog\Comment;
use Ramsey\Uuid\Guid\Guid;

class CommentsController
{
    private SQLiteCommentsRepository $commentsRepository;
    private SQLiteArticlesRepository $articlesRepository;

    public function __construct($databesePath) {
        $this->commentsRepository = new SQLiteCommentsRepository($databesePath);
        $this->articlesRepository = new SQLiteArticlesRepository($databesePath);
    }

    public function addComment(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['author_uuid'], $data['post_uuid'], $data['text'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. Required fields: author_uuid, post_uuid, text']);
            return;
        }

        $authorUuid = $data['author_uuid'];
        $postUuid = $data['post_uuid'];
        $text = $data['text'];

        try {
            $article = $this->articlesRepository->get(Guid::fromString($postUuid));

            $comment = new Comment(Guid::uuid4()->toString(), $authorUuid, $postUuid, $text);

            $this->commentsRepository->save($comment);

            http_response_code(201);
            echo json_encode([
                'message' => 'Comment successfully created.',
                'comment_id' => $comment->getId(),
            ]);
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

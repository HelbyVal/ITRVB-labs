<?php

namespace Helby\lessons\Controllers;

use Helby\lessons\Repositories\SQLiteLikesRepository;
use Helby\lessons\Blog\Like;
use Ramsey\Uuid\Guid\Guid;

class LikesController
{
    private SQLiteLikesRepository $likesRepository;

    public function __construct($databesePath) {
        $this->likesRepository = new SQLiteLikesRepository($databesePath);
    }

    public function addArticleLike(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['author_uuid'], $data['post_uuid'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. Required fields: author_uuid, post_uuid']);
            return;
        }

        $authorUuid = $data['author_uuid'];
        $postUuid = $data['post_uuid'];

        try {
            $article = $this->likesRepository->get(Guid::fromString($postUuid));

            $like = new Like(Guid::uuid4()->toString(), $authorUuid, $postUuid);

            $this->likesRepository->save($like);

            http_response_code(201);
            echo json_encode([
                'message' => 'Comment successfully created.',
                'comment_id' => $like->getId(),
            ]);
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    public function addCommentLike(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['author_uuid'], $data['post_uuid'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. Required fields: author_uuid, post_uuid']);
            return;
        }

        $authorUuid = $data['author_uuid'];
        $postUuid = $data['post_uuid'];

        try {
            $article = $this->likesRepository->get(Guid::fromString($postUuid));

            $like = new Like(Guid::uuid4()->toString(), $authorUuid, $postUuid);

            $this->likesRepository->save($like);

            http_response_code(201);
            echo json_encode([
                'message' => 'Comment successfully created.',
                'comment_id' => $like->getId(),
            ]);
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

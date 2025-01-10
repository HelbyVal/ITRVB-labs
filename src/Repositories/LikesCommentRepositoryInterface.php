<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\LikeComment;
use Ramsey\Uuid\UuidInterface;

interface LikesCommentRepositoryInterface
{
    public function get(UuidInterface $id): LikeComment;
    public function save(LikeComment $like): void;
}
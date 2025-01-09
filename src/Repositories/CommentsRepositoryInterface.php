<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\Comment;
use Ramsey\Uuid\UuidInterface;

interface CommentsRepositoryInterface
{
    public function get(UuidInterface $id): Comment;
    public function save(Comment $comment): void;
}
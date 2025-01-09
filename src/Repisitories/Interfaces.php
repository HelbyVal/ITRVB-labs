<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\Article;
use Helby\lessons\Blog\Comment;
use Ramsey\Uuid\UuidInterface;

interface ArticlesRepositoryInterface
{
    public function get(UuidInterface $id): Article;
    public function save(Article $article): void;
}

interface CommentsRepositoryInterface
{
    public function get(UuidInterface $id): Comment;
    public function save(Comment $comment): void;
}
<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\Article;
use Ramsey\Uuid\UuidInterface;

interface ArticlesRepositoryInterface
{
    public function get(UuidInterface $id): Article;
    public function save(Article $article): void;
}
<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\Like;
use Ramsey\Uuid\UuidInterface;

interface LikesRepositoryInterface
{
    public function get(UuidInterface $id): Like;
    public function save(Like $like): void;
}
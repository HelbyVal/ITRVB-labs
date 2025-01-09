<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\User;
use Ramsey\Uuid\UuidInterface;

interface UsersRepositoryInterface
{
    public function get(UuidInterface $id): User;
    public function save(User $user): void;
}
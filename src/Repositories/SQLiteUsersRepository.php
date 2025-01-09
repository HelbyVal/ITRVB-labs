<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\User;
use Helby\lessons\Blog\Name;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class SQLiteUsersRepository implements UsersRepositoryInterface
{
    private \SQLite3 $connection;

    public function __construct(string $dbFile)
    {
        $this->connection = new \SQLite3($dbFile);

        $this->connection->exec('CREATE TABLE IF NOT EXISTS users (
            id TEXT PRIMARY KEY,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            nickname TEXT NOT NULL
        )');
    }

    public function save(User $user): void
    {
        $stmt = $this->connection->prepare('
            INSERT INTO users (id, first_name, last_name, nickname) 
            VALUES (:id, :first_name, :last_name, :nickname)
        ');
        $stmt->bindValue(':id', $user->getId());
        $stmt->bindValue(':first_name', $user->getName()->getFirstName());
        $stmt->bindValue(':last_name', $user->getName()->getLastName());
        $stmt->bindValue(':nickname', $user->getNickname());

        $stmt->execute();
    }

    public function get(UuidInterface $id): User
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id->toString());
        $result = $stmt->execute();

        $userData = $result->fetchArray(SQLITE3_ASSOC);

        if (!$userData) {
            throw new \Exception("User with ID {$id->toString()} not found.");
        }

        $name = new Name($userData['first_name'], $userData['last_name']);
        return new User($id->toString(), $userData['nickname'], $name);
    }
}

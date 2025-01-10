<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\User;
use Helby\lessons\Blog\Name;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Psr\Log\LoggerInterface;

class SQLiteUsersRepository implements UsersRepositoryInterface
{
    private \SQLite3 $db;
    private LoggerInterface $logger;

    public function __construct(string $dbPath, LoggerInterface $logger)
    {
        $this->db = new \SQLite3($dbPath);
        $this->logger = $logger;
    }

    public function save(User $user): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO users (id, first_name, last_name, nickname) 
            VALUES (:id, :first_name, :last_name, :nickname)
        ');
        $stmt->bindValue(':id', $user->getId());
        $stmt->bindValue(':first_name', $user->getName()->getFirstName());
        $stmt->bindValue(':last_name', $user->getName()->getLastName());
        $stmt->bindValue(':nickname', $user->getNickname());

        $stmt->execute();

        $this->logger->info("User {$user->getId()} saved successfully.");
    }

    public function get(UuidInterface $id): User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id->toString());
        $result = $stmt->execute();

        $userData = $result->fetchArray(SQLITE3_ASSOC);

        if (!$userData) {
            $this->logger->warning("User with ID {$id->toString()} not found.");
            throw new \Exception("User with ID {$id->toString()} not found.");
        }

        $name = new Name($userData['first_name'], $userData['last_name']);
        return new User($id->toString(), $userData['nickname'], $name);
    }
}

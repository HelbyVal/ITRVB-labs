<?php

use PHPUnit\Framework\TestCase;
use Helby\lessons\Repositories\SQLiteUsersRepository;
use Helby\lessons\Blog\User;
use Helby\lessons\Blog\Name;
use Ramsey\Uuid\Guid\Guid;

class SQLiteUsersRepositoryTest extends TestCase
{
    private SQLiteUsersRepository $repository;
    private string $dbFile = __DIR__ . '/test.db'; 

    protected function setUp(): void
    {
        $this->repository = new SQLiteUsersRepository($this->dbFile);
        $this->initializeDatabase();
    }

    protected function tearDown(): void
    {
        if (file_exists($this->dbFile)) {
            unlink($this->dbFile);
        }
    }

    private function initializeDatabase(): void
    {
        $db = new SQLite3($this->dbFile);
        $db->exec('CREATE TABLE IF NOT EXISTS users (
            id TEXT PRIMARY KEY,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            nickname TEXT NOT NULL
        )');
    }

    public function testSaveUser(): void
    {
        $name = new Name('John', 'Doe');
        $user = new User(Guid::uuid4()->toString(), "johndog1337", $name);

        $this->repository->save($user);

        $retrievedUser = $this->repository->get(Guid::fromString($user->getId()));

        $this->assertEquals($user->getId(), $retrievedUser->getId());
        $this->assertEquals($user->getName()->getFirstName(), $retrievedUser->getName()->getFirstName());
        $this->assertEquals($user->getName()->getLastName(), $retrievedUser->getName()->getLastName());
        $this->assertEquals($user->getNickname(), $retrievedUser->getNickname());
    }

    public function testGetUserByUuid(): void
    {
        $name = new Name('Alice', 'Smith');
        $user = new User(Guid::uuid4()->toString(), "alice228", $name);

        $this->repository->save($user);

        $retrievedUser = $this->repository->get(Guid::fromString($user->getId()));

        $this->assertEquals($user->getId(), $retrievedUser->getId());
        $this->assertEquals($user->getName()->getFirstName(), $retrievedUser->getName()->getFirstName());
        $this->assertEquals($user->getNickname(), $retrievedUser->getNickname());
    }

    public function testUserNotFound(): void
    {
        $nonExistentUuid = Guid::uuid4();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("User with ID {$nonExistentUuid->toString()} not found.");

        $this->repository->get($nonExistentUuid);
    }
}

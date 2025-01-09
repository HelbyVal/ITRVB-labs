<?php

use PHPUnit\Framework\TestCase;
use Helby\lessons\Repositories\SQLiteCommentsRepository;
use Helby\lessons\Blog\Comment;
use Ramsey\Uuid\Guid\Guid;

class SQLiteCommentsRepositoryTest extends TestCase
{
    private SQLiteCommentsRepository $repository;
    private string $dbFile = __DIR__ . '/test.db'; 

    protected function setUp(): void
    {
        $this->repository = new SQLiteCommentsRepository($this->dbFile);
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
        $db->exec('CREATE TABLE IF NOT EXISTS comments (
            id TEXT PRIMARY KEY,
            author_id TEXT NOT NULL,
            article_id TEXT NOT NULL,
            text TEXT NOT NULL
        )');
    }

    public function testSaveComment(): void
    {
        $comment = new Comment(
            Guid::uuid4()->toString(),
            Guid::uuid4()->toString(),
            Guid::uuid4()->toString(),
            'Test comment text'
        );

        $this->repository->save($comment);

        $retrievedComment = $this->repository->get(Guid::fromString($comment->getId()));

        $this->assertEquals($comment->getId(), $retrievedComment->getId());
        $this->assertEquals($comment->getAuthorId(), $retrievedComment->getAuthorId());
        $this->assertEquals($comment->getArticleId(), $retrievedComment->getArticleId());
        $this->assertEquals($comment->getText(), $retrievedComment->getText());
    }

    public function testGetCommentByUuid(): void
    {
        $comment = new Comment(
            Guid::uuid4()->toString(),
            Guid::uuid4()->toString(),
            Guid::uuid4()->toString(),
            'Another test comment text'
        );

        $this->repository->save($comment);

        $retrievedComment = $this->repository->get(Guid::fromString($comment->getId()));

        $this->assertEquals($comment->getId(), $retrievedComment->getId());
        $this->assertEquals($comment->getText(), $retrievedComment->getText());
    }

    public function testCommentNotFound(): void
    {
        $nonExistentUuid = Guid::uuid4();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Comment with ID {$nonExistentUuid->toString()} not found.");

        $this->repository->get($nonExistentUuid);
    }
}

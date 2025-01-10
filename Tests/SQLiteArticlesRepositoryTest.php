<?php

use PHPUnit\Framework\TestCase;
use Helby\lessons\Repositories\SQLiteArticlesRepository;
use Helby\lessons\Blog\Article;
use Ramsey\Uuid\Guid\Guid;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class SQLiteArticlesRepositoryTest extends TestCase
{
    private SQLiteArticlesRepository $repository;
    private string $dbFile = __DIR__ . '/test.db'; 
    private Logger $logger;

    protected function setUp(): void
    {
        $this->logger = new Logger('test');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/test.log', Logger::DEBUG));
        $this->repository = new SQLiteArticlesRepository($this->dbFile, $this->logger);

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
        $db->exec('CREATE TABLE IF NOT EXISTS articles (
            id TEXT PRIMARY KEY,
            author_id TEXT NOT NULL,
            title TEXT NOT NULL,
            text TEXT NOT NULL
        )');
    }

    public function testSaveArticle(): void
    {
        $article = new Article(
            Guid::uuid4()->toString(), 
            'author-id',
            'Test Title',
            'Test content for article'
        );

        $this->repository->save($article);

        $retrievedArticle = $this->repository->get(Guid::fromString($article->getId()));

        $this->assertEquals($article->getId(), $retrievedArticle->getId());
        $this->assertEquals($article->getTitle(), $retrievedArticle->getTitle());
        $this->assertEquals($article->getText(), $retrievedArticle->getText());
    }

    public function testGetArticleByUuid(): void
    {

        $article = new Article(
            Guid::uuid4()->toString(),
            'author-id',
            'Another Test Title',
            'Some content for this article'
        );

        $this->repository->save($article);

        $retrievedArticle = $this->repository->get(Guid::fromString($article->getId()));

        $this->assertEquals($article->getId(), $retrievedArticle->getId());
        $this->assertEquals($article->getTitle(), $retrievedArticle->getTitle());
    }

    public function testArticleNotFound(): void
    {

        $nonExistentUuid = Guid::uuid4();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Article with ID {$nonExistentUuid->toString()} not found.");

        $this->repository->get($nonExistentUuid);
    }
}

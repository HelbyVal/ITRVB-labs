<?php
use PHPUnit\Framework\TestCase;
use Helby\lessons\Controllers\ArticlesController;
use Helby\lessons\Repositories\SQLiteArticlesRepository;
use Helby\lessons\Blog\Article;
use Ramsey\Uuid\Guid\Guid;
use SQLite3;

class ArticlesControllerTest extends TestCase
{
    private string $dbFile = __DIR__ . '/test.db';
    private ArticlesController $controller;

    protected function setUp(): void
    {
        $this->controller = new ArticlesController($this->dbFile);
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
        $db->exec('CREATE TABLE IF NOT EXISTS articles (
            id TEXT PRIMARY KEY,
            author_id TEXT NOT NULL,
            title TEXT NOT NULL,
            text TEXT NOT NULL
        )');
    }

    public function testAddArticleSuccess(): void
    {
        $authorUuid = Guid::uuid4()->toString();
        $title = 'Test Title';
        $text = 'Test content for article';

        $firstName = 'John';
        $lastName = 'Doe';
        $db = new SQLite3($this->dbFile);
        $db->exec("INSERT INTO users (id, first_name, last_name, nickname) VALUES ('$authorUuid', '$firstName', '$lastName', 'Test Author')");

        $data = [
            'author_uuid' => $authorUuid,
            'title' => $title,
            'text' => $text
        ];

        ob_start();
        $this->controller->addArticleMethod($data);
        $output = ob_get_clean();

        $this->assertStringContainsString('Article successfully created', $output);
    }

    public function testAddArticleInvalidUuid(): void
    {
        $data = [
            'author_uuid' => 'invalid-uuid',
            'title' => 'Test Title',
            'text' => 'Test content for article'
        ];

        ob_start();
        $this->controller->addArticleMethod($data);
        $output = ob_get_clean();

        $this->assertStringContainsString('Invalid input. Required field: author_uuid', $output);
    }

    public function testAddArticleUserNotFound(): void
    {
        $data = [
            'author_uuid' => Guid::uuid4()->toString(),
            'title' => 'Test Title',
            'text' => 'Test content for article'
        ];

        ob_start();
        $this->controller->addArticleMethod($data);
        $output = ob_get_clean();

        $this->assertStringContainsString('User not found', $output);
    }

    public function testAddArticleMissingFields(): void
    {
        $data = [
            'author_uuid' => Guid::uuid4()->toString(),
        ];

        ob_start();
        $this->controller->addArticleMethod($data);
        $output = ob_get_clean();

        $this->assertStringContainsString('Invalid input. Required fields: author_uuid, title, text', $output);
    }


}

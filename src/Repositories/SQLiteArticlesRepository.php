<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\Article;
use Ramsey\Uuid\UuidInterface;
use SQLite3;

class SQLiteArticlesRepository implements ArticlesRepositoryInterface
{
    private SQLite3 $db;

    public function __construct(string $databasePath)
    {
        $this->db = new SQLite3($databasePath);
    }

    public function get(UuidInterface $id): Article
    {
        $stmt = $this->db->prepare('SELECT * FROM articles WHERE id = :id');
        $stmt->bindValue(':id', $id->toString(), SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$result) {
            throw new \Exception("Article with ID {$id->toString()} not found.");
        }

        return new Article(
            $result['id'],
            $result['author_id'],
            $result['title'],
            $result['text']
        );
    }

    public function save(Article $article): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO articles (id, author_id, title, text)
            VALUES (:id, :author_id, :title, :text)
            ON CONFLICT(id) DO UPDATE SET
                author_id = excluded.author_id,
                title = excluded.title,
                text = excluded.text
        ');
        $stmt->bindValue(':id', $article->getId(), SQLITE3_TEXT);
        $stmt->bindValue(':author_id', $article->getAuthorId(), SQLITE3_TEXT);
        $stmt->bindValue(':title', $article->getTitle(), SQLITE3_TEXT);
        $stmt->bindValue(':text', $article->getText(), SQLITE3_TEXT);
        $stmt->execute();
    }

    public function delete(UuidInterface $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM articles WHERE id = :id');
        $stmt->bindValue(':id', $id->toString(), SQLITE3_TEXT);
        $stmt->execute();

        if ($this->db->changes() === 0) {
            throw new \Exception("Article with ID {$id->toString()} not found.");
        }
    }
}

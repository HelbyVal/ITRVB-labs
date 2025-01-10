<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\Like;
use Ramsey\Uuid\UuidInterface;
use SQLite3;
use Psr\Log\LoggerInterface;

class SQLiteLikesRepository implements LikesRepositoryInterface
{
    private SQLite3 $db;
    private LoggerInterface $logger;

    public function __construct(string $databasePath, LoggerInterface $logger)
    {
        $this->db = new SQLite3($databasePath);
        $this->logger = $logger;
    }

    public function get(UuidInterface $id): Like
    {
        $stmt = $this->db->prepare('SELECT * FROM likes WHERE id = :id');
        $stmt->bindValue(':id', $id->toString(), SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$result) {
            $this->logger->warning("Like with ID {$id->toString()} not found.");
            throw new \Exception("Like with ID {$id->toString()} not found.");
        }

        return new Like(
            $result['id'],
            $result['author_id'],
            $result['article_id']
        );
    }

    public function save(Like $like): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO likes (id, author_id, article_id)
            VALUES (:id, :author_id, :article_id)
            ON CONFLICT(id) DO UPDATE SET
                author_id = excluded.author_id,
                article_id = excluded.article_id
        ');
        $stmt->bindValue(':id', $like->getId(), SQLITE3_TEXT);
        $stmt->bindValue(':author_id', $like->getAuthorId(), SQLITE3_TEXT);
        $stmt->bindValue(':article_id', $like->getArticleId(), SQLITE3_TEXT);
        $stmt->execute();

        $this->logger->info("Like {$like->getId()} saved successfully.");
    }

}
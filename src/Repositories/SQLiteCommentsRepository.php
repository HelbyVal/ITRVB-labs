<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\Comment;
use Ramsey\Uuid\UuidInterface;
use SQLite3;

class SQLiteCommentsRepository implements CommentsRepositoryInterface
{
    private SQLite3 $db;

    public function __construct(string $databasePath)
    {
        $this->db = new SQLite3($databasePath);
    }

    public function get(UuidInterface $id): Comment
    {
        $stmt = $this->db->prepare('SELECT * FROM comments WHERE id = :id');
        $stmt->bindValue(':id', $id->toString(), SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$result) {
            throw new \Exception("Comment with ID {$id->toString()} not found.");
        }

        return new Comment(
            $result['id'],
            $result['author_id'],
            $result['article_id'],
            $result['text']
        );
    }

    public function save(Comment $comment): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO comments (id, author_id, article_id, text)
            VALUES (:id, :author_id, :article_id, :text)
            ON CONFLICT(id) DO UPDATE SET
                author_id = excluded.author_id,
                article_id = excluded.article_id,
                text = excluded.text
        ');
        $stmt->bindValue(':id', $comment->getId(), SQLITE3_TEXT);
        $stmt->bindValue(':author_id', $comment->getAuthorId(), SQLITE3_TEXT);
        $stmt->bindValue(':article_id', $comment->getArticleId(), SQLITE3_TEXT);
        $stmt->bindValue(':text', $comment->getText(), SQLITE3_TEXT);
        $stmt->execute();
    }
}
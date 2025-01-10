<?php

namespace Helby\lessons\Repositories;

use Helby\lessons\Blog\LikeComment;
use Ramsey\Uuid\UuidInterface;
use SQLite3;

class SQLiteLikesCommentRepository implements LikesCommentRepositoryInterface
{
    private SQLite3 $db;

    public function __construct(string $databasePath)
    {
        $this->db = new SQLite3($databasePath);
    }

    public function get(UuidInterface $id): LikeComment
    {
        $stmt = $this->db->prepare('SELECT * FROM likes WHERE id = :id');
        $stmt->bindValue(':id', $id->toString(), SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$result) {
            throw new \Exception("Like with ID {$id->toString()} not found.");
        }

        return new LikeComment(
            $result['id'],
            $result['author_id'],
            $result['comment_id']
        );
    }

    public function save(LikeComment $like): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO likes (id, author_id, comment_id)
            VALUES (:id, :author_id, :comment_id)
            ON CONFLICT(id) DO UPDATE SET
                author_id = excluded.author_id,
                comment_id = excluded.comment_id
        ');
        $stmt->bindValue(':id', $like->getId(), SQLITE3_TEXT);
        $stmt->bindValue(':author_id', $like->getAuthorId(), SQLITE3_TEXT);
        $stmt->bindValue(':comment_id', $like->getCommentId(), SQLITE3_TEXT);
        $stmt->execute();
    }

}
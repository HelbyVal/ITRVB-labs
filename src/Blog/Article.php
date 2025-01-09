<?php

namespace Helby\lessons\Blog;
use Ramsey\Uuid\Uuid;

class Article {
    public function __construct(
        private string $id = '',
        private string $authorId,
        private string $title,
        private string $text
    ) {
        // Генерация UUID, если не передан
        $this->id = $this->id ?: Uuid::uuid4()->toString();
    }
    public function __toString()
    {
        return
            "ID : " . $this->getId() . "<br>" .
            "Автор ID : " . $this->getAuthorId() ."<br>".
            "Заголовок : " . $this->getTitle() . "<br>" .
            "Текст : " . $this->getText() . "<br>";
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getText()
    {
        return $this->text;
    }
}

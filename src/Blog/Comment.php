<?php

namespace Helby\lessons\Blog;

class Comment {
    public function __construct(private $id, private $authorId, private $articleId, private $text) {

    }

    public function __toString()
    {
        return
            "ID : " . $this->getId() . "<br>" .
            "Автор ID : " . $this->getAuthorId() ."<br>".
            "Статья ID : " . $this->getArticleId() . "<br>" .
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

    public function getArticleId()
    {
        return $this->articleId;
    }

    public function getText()
    {
        return $this->text;
    }
}

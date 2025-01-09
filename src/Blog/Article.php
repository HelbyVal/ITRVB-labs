<?php

namespace Helby\lessons\Blog;

class Article {
    public function __construct(public $id, private $authorId, private $title, private $text) {

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

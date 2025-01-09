<?php

namespace Helby\lessons\Blog;

class User {
    public function __construct(public $id, private readonly Name $name) {

    }

    public function __toString() {
        return
            "ID : " . $this->getId() . "<br>" .
            $this->getName() . "<br>";
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
}

<?php

namespace Helby\lessons\Blog;
class Name {
    public function __construct(private $firstName, private $lastName){

    }

    public function __toString() {
        return
            "Имя : " . $this->getFirstName() . "<br>" .
            "Фамилия : " . $this->getLastName();
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

}
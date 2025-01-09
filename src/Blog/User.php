<?php

namespace Helby\lessons\Blog;

use Ramsey\Uuid\Uuid;

class User {
    public function __construct(
        private string $id,
        private string $nickname,
        private Name $name) {
        // Генерация UUID, если не передан
        $this->id = $this->id ?: Uuid::uuid4()->toString();
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

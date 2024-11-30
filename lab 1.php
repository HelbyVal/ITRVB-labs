<?php

// Базовый класс для продукта
abstract class Product {
    protected $id;
    protected $name;
    protected $price;
    protected $description;
    protected $stock;
    protected $category;
    
    public function __construct($id, $name, $price, $description, $stock, $category) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->stock = $stock;
        $this->category = $category;
    }
    // Метод для получения цены товара
    public function getPrice() {
        return $this->price;
    }
    // Метод для обновления цены
    public function setPrice($price) {
        $this->price = $price;
    }
    // Метод для обновления количества товара
    public function updateStock($quantity) {
        $this->stock += $quantity;
    }
    // Абстрактный метод для расчета скидки
    abstract public function calculateDiscount();
}

// Наследник класса Product - Электронный товар
class ElectronicProduct extends Product {
    private $warranty; // Гарантия
    private $brand; // Бренд
    
    public function __construct($id, $name, $price, $description, $stock, $category, $warranty, $brand) {
        parent::__construct($id, $name, $price, $description, $stock, $category);
        $this->warranty = $warranty;
        $this->brand = $brand;
    }
    
    public function calculateDiscount() {
        // Скидка 10% для электроники
        return $this->price * 0.1;
    }
    // Метод продления гарантии
    public function extendWarranty($months) {
        $this->warranty += $months;
    }
}

// Наследник класса Product - Одежда
class ClothingProduct extends Product {
    private $size;
    private $color;
    private $material;
    
    public function __construct($id, $name, $price, $description, $stock, $category, $size, $color, $material) {
        parent::__construct($id, $name, $price, $description, $stock, $category);
        $this->size = $size;
        $this->color = $color;
        $this->material = $material;
    }
    // Перегруженный метод расчета скидки для одежды
    public function calculateDiscount() {
        // Скидка 15% для одежды
        return $this->price * 0.15;
    }
}

// Класс для корзины покупок
class Cart {
    private $items = [];
    private $totalPrice = 0;
    
    public function addItem(Product $product, $quantity = 1) {
        $this->items[] = ['product' => $product, 'quantity' => $quantity];
        $this->calculateTotal();
    }
    
    public function removeItem($productId) {
        foreach ($this->items as $key => $item) {
            if ($item['product']->id === $productId) {
                unset($this->items[$key]);
                break;
            }
        }
        $this->calculateTotal();
    }
    
    private function calculateTotal() {
        $this->totalPrice = 0;
        foreach ($this->items as $item) {
            $this->totalPrice += ($item['product']->getPrice() * $item['quantity']);
        }
    }
    
    public function getTotalPrice() {
        return $this->totalPrice;
    }
}

// Класс для пользователя
class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $address;
    private $orderHistory = []; // История заказов
    
    public function __construct($id, $username, $email, $password, $address) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->address = $address;
    }
    
    public function updateProfile($username, $email, $address) {
        $this->username = $username;
        $this->email = $email;
        $this->address = $address;
    }
    
    public function addOrder($order) {
        $this->orderHistory[] = $order;
    }
}

// Класс для отзыва
class Review {
    private $id;
    private $productId; // ID продукта, можно использовать для связи в бдate 
    private $userId; // Аналогично и с user
    private $rating; // Оценка
    private $comment; // Комментарий
    private $date; // Дата отзыва
    
    public function __construct($id, $productId, $userId, $rating, $comment) {
        $this->id = $id;
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = new DateTime();
    }
    
    public function updateReview($rating, $comment) {
        $this->rating = $rating;
        $this->comment = $comment;
    }
}

?>

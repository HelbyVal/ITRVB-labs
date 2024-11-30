<?php

abstract class Nomenclature {
    protected $name;
    protected $basePrice;
    
    public function __construct($name, $basePrice) {
        $this->name = $name;
        $this->basePrice = $basePrice;
    }
    
    abstract public function calculateFinalPrice();
    
    public function getName() {
        return $this->name;
    }
    
    public function getBasePrice() {
        return $this->basePrice;
    }
}

class DigitalProduct extends Nomenclature {
    public function calculateFinalPrice() {
        // Цифровой товар стоит в 2 раза меньше базовой цены по сути
        return $this->basePrice / 2;
    }
}

class PhysicalProduct extends Nomenclature {
    protected $quantity;
    
    public function __construct($name, $basePrice, $quantity) {
        parent::__construct($name, $basePrice);
        $this->quantity = $quantity;
    }
    
    public function calculateFinalPrice() {
        return $this->basePrice * $this->quantity;
    }
}

class WeightProduct extends Nomenclature {
    protected $weight;
    
    public function __construct($name, $basePrice, $weight) {
        parent::__construct($name, $basePrice);
        $this->weight = $weight;
    }
    
    public function calculateFinalPrice() {
        return $this->basePrice * $this->weight;
    }
}

$digitalProduct = new DigitalProduct("Электронная книга", 750);
$physicalProduct = new PhysicalProduct("Книга", 750, 2);
$weightProduct = new WeightProduct("Яблоки", 100, 2.5);


echo "Цифровой товар '{$digitalProduct->getName()}': " . $digitalProduct->calculateFinalPrice() . " руб.\n";
echo "Физический товар '{$physicalProduct->getName()}': " . $physicalProduct->calculateFinalPrice() . " руб.\n";
echo "Весовой товар '{$weightProduct->getName()}': " . $weightProduct->calculateFinalPrice() . " руб.\n";

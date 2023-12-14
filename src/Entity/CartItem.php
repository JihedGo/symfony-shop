<?php

namespace App\Entity;

class CartItem
{
    public Product $product;
    public int $quantity;
    public float $totalPrice;
    public function __construct($product, $quantity, $totalPrice)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->totalPrice = $totalPrice;
    }
}

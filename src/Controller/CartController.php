<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/add-to-cart/{id}', name: 'app_cart')]
    public function index(Product $product, RequestStack $requestStack): Response
    {
        // $requestStack->getSession()->clear();
        $session = $requestStack->getSession()->get('cart');
        if ($session) {
            $session->count = $session->count + 1;

            $productFound = false;
            foreach ($session->items as $item) {
                if ($item->product->getId() == $product->getId()) {
                    $item->quantity = $item->quantity + 1;
                    $item->totalPrice = $item->quantity * $item->product->getPrice();
                    $productFound = true;
                    break;
                }
            }

            if (!$productFound) {
                $cartItem = new CartItem($product, 1, $product->getPrice() * 1);
                $session->items[] = $cartItem;
            }

            $requestStack->getSession()->set('cart', $session);
        } else {
            $cart = new Cart();
            $cartItem = new CartItem($product, 1, $product->getPrice() * 1);
            $cart->count = 1;
            $cart->items[] = $cartItem;
            $cart->total = 0;
            $requestStack->getSession()->set('cart', $cart);
        }
    die;
    }
}

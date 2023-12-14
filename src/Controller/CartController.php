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

        //$requestStack->getSession()->clear();
        $session = $requestStack->getSession()->get('cart');
        if (!$session) {
            $cart = new Cart();
            $cartItem = new CartItem($product, 1, $product->getPrice() * 1);
            $cart->count = 1;
            $cart->items[] = $cartItem;
            $cart->total   = 0;
            $session = $requestStack->getSession()->set('cart', $cart);
        } else {
            $session->count = $session->count + 1;

            foreach ($session->items as &$item) {
                if ($item->product->getId() === $product->getId()) {
                    $item->quantity = $item->quantity + 1;
                    $item->totalPrice = $item->quantity * $item->product->getPrice();
                    break;
                } else {
                    $cartItem = new CartItem($product, 1, $product->getPrice() * 1);
                    //dd($cartItem);
                    $session->items[] = $cartItem;
                }
            }
            $requestStack->getSession()->clear();
            $requestStack->getSession()->set('cart', $session);
        }
        dump($session);
        /* if ($cart) {
            $cart->count = $cart->count + 1;
            foreach ($cart->items as &$cartItem) {
                dump($cartItem->product->getId(), $product->getId());
                dump($cartItem->product->getId() === $product->getId());
                if ($cartItem->product->getId() === $product->getId()) {
                    $cartItem->quantity = $cartItem->quantity + 1;
                    $cartItem->totalPrice = $cartItem->product->getPrice() * $cartItem->quantity;
                    break;
                } else {
                    $cartIt = new CartItem($product, 1, $product->getPrice() * 1);
                    $cart->items[] = $cartIt;
                }
            }
            dd($cart);
        } else {
            $cart = new Cart();
            $cart->count = $cart->count + 1;
            $cartItem = new CartItem($product, 1, $product->getPrice() * 1);
            $cart->items[] = $cartItem;
            $cart = $requestStack->getSession()->set('cart', $cart);
            die();
            return $this->redirectToRoute('app_product');
        }*/
        die();
    }
}

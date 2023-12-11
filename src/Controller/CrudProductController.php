<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud/product')]
class CrudProductController extends AbstractController
{
    #[Route('/', name: 'list_product')]
    public function listProducts(RequestStack $session): Response
    {
        $products = [];
        if (!$session->getSession()->get('products')) {
            $session->getSession()->set('products', $products);
        } else {
            $products = $session->getSession()->get('products');
        }
        return $this->render('crud_product/index.html.twig', [
            'products' => $products,
        ]);
    }
    #[Route('/add', name: 'add_product')]
    public function addProduct(Request $request, RequestStack $session): Response
    {
        if ($request->request->get('product') && $request->request->get('price') && $request->request->get('qte')) {
            if (!$session->getSession()->get('products')) {
                $products = [];
                array_push($products, [
                    'id' => uniqid(),
                    'product' => $request->request->get('product'),
                    'price'   => $request->request->get('price'),
                    'qte'     => $request->request->get('qte')
                ]);
                $session->getSession()->set('products', $products);
                return $this->redirectToRoute('list_product');
            } else {
                $oldProducts = (array)$session->getSession()->get('products');
                $session->getSession()->clear();
                array_push($oldProducts, [
                    'id' => uniqid(),
                    'product' => $request->request->get('product'),
                    'price'   => $request->request->get('price'),
                    'qte'     => $request->request->get('qte')
                ]);
                $session->getSession()->set('products', $oldProducts);
                return $this->redirectToRoute('list_product');
            }
        } else {
            return $this->render('crud_product/add.html.twig', []);
        }
    }
    #[Route('/{id}/delete', name: 'delete_product')]
    public function deleteProduct(string $id, RequestStack $session): Response
    {
        $newProducts = [];
        $products = $session->getSession()->get('products');
        foreach ($products as $product) {
            if ($product['id'] == $id) {
                continue;
            } else {
                array_push($newProducts, $product);
            }
        }
        $session->getSession()->clear();
        $session->getSession()->set('products', $newProducts);
        return $this->redirectToRoute('list_product');
    }
    #[Route('/{id}/update', name: 'update_product')]
    public function updateProduct(string $id, Request $request, RequestStack $session): Response
    {
        if ($request->request->get('product') && $request->request->get('price') && $request->request->get('qte')) {
            $updatedProduct = [
                'id' => $id,
                'product' => $request->request->get('product'),
                'price'   => $request->request->get('price'),
                'qte'     => $request->request->get('qte')
            ];
            $products = $session->getSession()->get('products');
            foreach ($products as &$product) {
                if ($product['id'] == $id) {
                    $product = $updatedProduct;
                    break;
                }
            }
            $session->getSession()->clear();
            $session->getSession()->set('products', $products);
            return $this->redirectToRoute('list_product');
        } else {
            $p = [];
            $products = $session->getSession()->get('products');
            foreach ($products as &$product) {
                if ($product['id'] == $id) {
                    $p = $product;
                    break;
                }
            }
            return $this->render('crud_product/update.html.twig', ['product' => $p]);
        }
    }

    #[Route('/{id}/view', name: 'view_product')]
    public function readSingleProduct(string $id, RequestStack $session): Response
    {
        $products = $session->getSession()->get('products');
        $p = [];
        foreach ($products as $product) {
            if ($product['id'] == $id) {
                $p = $product;
                break;
            }
        }

        return $this->render('crud_product/view.html.twig', ['product' => $p]);
    }
}

<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route("/", methods: ["POST", "GET"], name: "route_index")]
    public function index(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('index.html.twig', ['categories' => $categories]);
    }
    #[Route("/contact", methods: ["POST", "GET"], name: "route_contact")]
    function contact()
    {
        return $this->render('contact.html.twig');
    }

    #[Route("/about-us", methods: ["POST", "GET"], name: "route_aboutus")]
    function aboutUs()
    {
        return $this->render('base.html.twig');
    }

    #[Route("/products", methods: ["POST", "GET"], name: "route_products")]
    function products()
    {
        return $this->render('base.html.twig');
    }
}

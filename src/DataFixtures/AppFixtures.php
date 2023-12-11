<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $categories = [];
    private $catRepo;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->catRepo = $categoryRepository;
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 3; $i++) {
            $category = new Category;
            $category->setName("category $i");
            $category->setDescription("category description");
            array_push($this->categories, "category_$i");
            $this->setReference("category_$i", $category);
            $manager->persist($category);
        }
        foreach ($this->categories as $category) {
            for ($i = 0; $i < mt_rand(5, 10); $i++) {
                $product = new Product();
                $product->setCategory($this->getReference($category))
                    ->setName("product $i")
                    ->setDescription("description $i")
                    ->setIsAvailable([true, false][mt_rand(0, 1)])
                    ->setQuantity(mt_rand(2, 10))
                    ->setPrice(mt_rand(100, 999));
                $manager->persist($product);
            }
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'name' => 'Pizza Margherita',
                'description' => 'Pizza avec mozzarella et tomates',
                'price' => 4.50,
                'image' => 'pizza-margherita.jpg',
                'rating' => 4.2,
                'isAvailable' => true
            ],
            [
                'name' => 'Lasagnes Bolognaise',
                'description' => 'Lasagnes traditionnelles à la sauce bolognaise',
                'price' => 5.90,
                'image' => 'lasagnes-bolognaise.jpg',
                'rating' => 4.5,
                'isAvailable' => true
            ],
            [
                'name' => 'Poissons Panées',
                'description' => 'Poissons croustillantes panées',
                'price' => 7.20,
                'image' => 'poissons-panees.jpg',
                'rating' => 3.8,
                'isAvailable' => false
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData['name'])
                    ->setDescription($productData['description'])
                    ->setPrice($productData['price'])
                    ->setImage($productData['image'])
                    ->setRating($productData['rating'])
                    ->setIsAvailable($productData['isAvailable']);
            
            $manager->persist($product);
        }

        $manager->flush();
    }
}
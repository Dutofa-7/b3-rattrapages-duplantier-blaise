<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'get_products', methods: ['GET'])]
    public function getProducts(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $products = $productRepository->findAll();
        $data = $serializer->serialize($products, 'json');
        
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/products/{id}', name: 'get_product', methods: ['GET'])]
    public function getProduct(Product $product, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($product, 'json');
        
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/products/{id}/rate', name: 'rate_product', methods: ['POST'])]
    public function rateProduct(Product $product, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['rating']) || $data['rating'] < 0 || $data['rating'] > 5) {
            return new JsonResponse(['error' => 'Rating must be between 0 and 5'], Response::HTTP_BAD_REQUEST);
        }
        
        $product->setRating($data['rating']);
        $em->flush();
        
        return new JsonResponse(['message' => 'Product rated successfully'], Response::HTTP_OK);
    }
}
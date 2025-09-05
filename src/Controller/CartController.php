<?php

namespace App\Controller;

use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CartController extends AbstractController
{
    #[Route('/carts', name: 'create_cart', methods: ['POST'])]
    public function createCart(EntityManagerInterface $em): JsonResponse
    {
        $cart = new Cart();
        $cart->setStatus('active');
        $cart->setCreatedAt(new \DateTimeImmutable());
        
        $em->persist($cart);
        $em->flush();
        
        return new JsonResponse([
            'id' => $cart->getId(),
            'status' => $cart->getStatus()
        ]);
    }

    #[Route('/carts/{id}', name: 'get_cart', methods: ['GET'])]
    public function getCart(int $id, EntityManagerInterface $em): JsonResponse
    {
        $cart = $em->getRepository(Cart::class)->find($id);
        
        if (!$cart) {
            return new JsonResponse(['error' => 'Cart not found'], 404);
        }
        
        $cartData = [
            'id' => $cart->getId(),
            'status' => $cart->getStatus(),
            'cartItems' => []
        ];
        
        foreach ($cart->getCartItems() as $item) {
            $cartData['cartItems'][] = [
                'id' => $item->getId(),
                'quantity' => $item->getQuantity(),
                'product' => [
                    'id' => $item->getProduct()->getId(),
                    'name' => $item->getProduct()->getName(),
                    'price' => (float) $item->getProduct()->getPrice(),
                    'description' => $item->getProduct()->getDescription(),
                ]
            ];
        }
        
        return new JsonResponse($cartData);
    }

    #[Route('/carts/{id}/validate', name: 'validate_cart', methods: ['PATCH'])]
    public function validateCart(int $id, EntityManagerInterface $em): JsonResponse
    {
        $cart = $em->getRepository(Cart::class)->find($id);
        
        if (!$cart) {
            return new JsonResponse(['error' => 'Cart not found'], 404);
        }
        
        $cart->setStatus('validated');
        $cart->setUpdatedAt(new \DateTime());
        $em->flush();
        
        return new JsonResponse(['message' => 'Cart validated successfully']);
    }
}
<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
        
        return new JsonResponse(['id' => $cart->getId(), 'status' => $cart->getStatus()], Response::HTTP_CREATED);
    }

    #[Route('/carts/{id}', name: 'get_cart', methods: ['GET'])]
    public function getCart(Cart $cart, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($cart, 'json', ['groups' => ['cart']]);
        
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/carts/{id}/validate', name: 'validate_cart', methods: ['PATCH'])]
    public function validateCart(Cart $cart, EntityManagerInterface $em): JsonResponse
    {
        $cart->setStatus('validated');
        $cart->setUpdatedAt(new \DateTime());
        $em->flush();
        
        return new JsonResponse(['message' => 'Cart validated successfully'], Response::HTTP_OK);
    }
}
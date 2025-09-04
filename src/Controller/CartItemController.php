<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CartItemController extends AbstractController
{
    #[Route('/cart-items', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['cart_id'], $data['product_id'], $data['quantity'])) {
            return new JsonResponse(['error' => 'cart_id, product_id and quantity are required'], Response::HTTP_BAD_REQUEST);
        }
        
        $cart = $em->getRepository(Cart::class)->find($data['cart_id']);
        $product = $em->getRepository(Product::class)->find($data['product_id']);
        
        if (!$cart || !$product) {
            return new JsonResponse(['error' => 'Cart or Product not found'], Response::HTTP_NOT_FOUND);
        }
        
        $cartItem = new CartItem();
        $cartItem->setCart($cart);
        $cartItem->setProduct($product);
        $cartItem->setQuantity($data['quantity']);
        
        $em->persist($cartItem);
        $em->flush();
        
        return new JsonResponse(['id' => $cartItem->getId(), 'message' => 'Item added to cart'], Response::HTTP_CREATED);
    }

    #[Route('/cart-items/{id}', name: 'remove_from_cart', methods: ['DELETE'])]
    public function removeFromCart(CartItem $cartItem, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($cartItem);
        $em->flush();
        
        return new JsonResponse(['message' => 'Item removed from cart'], Response::HTTP_OK);
    }
}
<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{


    function deleteObjById(&$array, $id) {
        foreach ($array as $key => $obj) {
            if ($obj['id'] == $id) {
                unset($array[$key]);
                return $array;
            }
        }
        return false;
    }

    #[Route('/carts/validate', name: 'app_cart_validate', methods: ['POST'])]
    public function validateProduct(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $session = $request->getSession();
        $cart = $session->get('cart') ?? [];
        if (count($cart) === 0)
            return $this->json([
                'error' => 'Cart is empty'],404);

        $sum = 0;

        $order = new Order();
        foreach ($cart as $product) {
            $_product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $product['id']]);
            $sum += $_product->getPrice();
            $order->addProduct($_product);
        }

        $order->setTotalPrice($sum);
        $order->setCreationDate();

        $entityManager->persist($order);
        $entityManager->flush();
        return $this->json([
            "cart" => $session->get("cart")
        ],201);
    }
    #[Route('/carts/{productId}', name: 'app_cart_create', methods: ['POST'])]
    public function addProduct(EntityManagerInterface $entityManager, Request $request, $productId): JsonResponse
    {

        if (!is_numeric($productId))
            return $this->json([
                'error' => 'Product id must be a number'],400);

        $product = $entityManager->getRepository(Product::class)->findById($productId);

        if (count($product) === 0)
            return $this->json([
                'error' => 'Product not found'],404);

        $session = $request->getSession();
        $cart = $session->get('cart') ?? [];
        $cart[] = $product[0]->getJson();
        $session->set('cart', $cart);


        return $this->json([
            "cart" => $session->get("cart")
        ],201);
    }

    #[Route('/carts/{productId}', name: 'app_cart_delete', methods: ['DELETE'])]
    public function deleteProduct(EntityManagerInterface $entityManager, Request $request, $productId): JsonResponse
    {

        if (!is_numeric($productId))
            return $this->json([
                'error' => 'Product id must be a number'],400);

        $session = $request->getSession();

        $cart = $session->get('cart') ?? [];

        $_productId = $this->deleteObjById($cart, $productId);

        if ($_productId === false)
            return $this->json([
                'error' => 'Product not found in cart'],400);

        $session->set('cart', $cart);

        return $this->json([
            "cart" => $session->get("cart")
        ]);
    }

    #[Route('/carts', name: 'app_cart_get', methods: ['GET'])]
    public function getProduct(Request $request): JsonResponse
    {
        $session = $request->getSession();

        return $this->json([
            "cart" => $session->get("cart")
        ]);
    }
}

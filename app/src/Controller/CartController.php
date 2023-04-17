<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{


    function getObjById($array, $id)
    {
        foreach ($array as $obj) {
            if ($obj['id'] == $id) {
                return $obj;
            }
        }
        return null;
    }


    #[Route('/carts/{productId}', name: 'app_cart_create', methods: ['POST'])]
    public function addProduct(EntityManagerInterface $entityManager, Request $request, $productId): JsonResponse
    {

        if (!is_numeric($productId))
            return $this->json([
                'error' => 'Product id must be a number']);

        $product = $entityManager->getRepository(Product::class)->findById($productId);

        if (count($product) === 0)
            return $this->json([
                'error' => 'Product not found']);

        $session = $request->getSession();
        $cart = $session->get('cart') ?? [];
        $cart[] = $product;
        $session->set('cart', $cart);


        return $this->json([
            "cart" => $session->get("cart")
        ]);
    }

    #[Route('/carts/{productId}', name: 'app_cart_delete', methods: ['DELETE'])]
    public function deleteProduct(EntityManagerInterface $entityManager, Request $request, $productId): JsonResponse
    {

        if (!is_numeric($productId))
            return $this->json([
                'error' => 'Product id must be a number']);

        $session = $request->getSession();

        $cart = $session->get('cart') ?? [];

        $_productId = $this->getObjById($cart, $productId);

        dd($productId);

        if (count($_productId) === false)
            return $this->json([
                'error' => 'Product not found in cart']);

        $session->set('cart', $cart);


        $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $productId]);
        if ($product === null)
            return $this->json([
                'error' => 'Product not found']);

        return $this->json([
            "cart" => $session->get("cart")
        ]);
    }

    #[Route('/carts', name: 'app_cart', methods: ['GET'])]
    public function getProduct(EntityManagerInterface $entityManager, Request $request, $productId): JsonResponse
    {

        if (!is_numeric($productId))
            return $this->json([
                'error' => 'Product id must be a number']);

        $product = $entityManager->getRepository(Product::class)->findById($productId);

        if (count($product) === 0)
            return $this->json([
                'error' => 'Product not found']);

        $session = $request->getSession();
        $cart = $session->get('cart') ?? [];
        $cart[] = $product;
        $session->set('cart', $cart);


        return $this->json([
            "cart" => $session->get("cart")
        ]);
    }

    #[Route('/carts/validate', name: 'app_cart', methods: ['POST'])]
    public function validateProduct(EntityManagerInterface $entityManager, Request $request, $productId): JsonResponse
    {

        if (!is_numeric($productId))
            return $this->json([
                'error' => 'Product id must be a number']);

        $product = $entityManager->getRepository(Product::class)->findById($productId);

        if (count($product) === 0)
            return $this->json([
                'error' => 'Product not found']);

        $session = $request->getSession();
        $cart = $session->get('cart') ?? [];
        $cart[] = $product;
        $session->set('cart', $cart);


        return $this->json([
            "cart" => $session->get("cart")
        ]);
    }
}

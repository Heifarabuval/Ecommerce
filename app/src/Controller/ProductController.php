<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_product_readall',methods:['GET'])]
    public function readAll(EntityManagerInterface $entityManager): JsonResponse
    {
        dd($entityManager->getRepository(Product::class )->findAll());
        return $this->json([
            'message' => 'Read all',
        
        ]);
    }


    #[Route('/products/{productId}', name: 'app_product_read',methods:['GET'])]
    public function read($productId): JsonResponse
    {
        return $this->json([
            'message' => 'Get one '.$productId,
        ]);
    }


    #[Route('/products', name: 'app_product_create',methods:['POST'])]
    public function create(): JsonResponse
    {
        return $this->json([
            'message' => 'Created',
        ]);
    }

        #[Route('/products/{productId}', name: 'app_product_update',methods:['PATCH'])]
    public function update(): JsonResponse
    {
        return $this->json([
            'message' => 'Updated',
        ]);
    }

    
    #[Route('/product', name: 'app_product_delete',methods:['DELETE'])]
    public function delete(): JsonResponse
    {
        return $this->json([
            'message' => 'DELETE',
        
        ]);
    }
}

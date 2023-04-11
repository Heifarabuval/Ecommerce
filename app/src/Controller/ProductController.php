<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\Json;
use App\Utils\Validate;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Requirement\Requirement;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_product_readall',methods:['GET'])]
    public function readAll(EntityManagerInterface $entityManager,): JsonResponse
    {
        $product = $entityManager->getRepository(Product::class )->findAll();
        $arrayProduct = array_map(function ($product) {return $product->getJson();}, $product);

        return $this->json([
            "products"=>$arrayProduct,
        ]);
    }


    #[Route('/products/{productId}', name: 'app_product_read',methods:['GET'],requirements:['id'=>Requirement::DIGITS])]
    public function read(EntityManagerInterface $entityManager, $productId): JsonResponse
    {
        if($productId)
        dd($entityManager->getRepository(Product::class )->findById($productId));
        
        return $this->json([
            'message' => 'Get one '.$productId,
        ]);
    }


    #[Route('/products', name: 'app_product_create',methods:['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request,ValidatorInterface $validator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $errors = Json::getJsonBody($request);

        $product = new Product;
    

        $product->setName($requestData["name"]?? null);
        $product->setDescription($requestData["description"]?? null);
        $product->setPhoto($requestData["photo"]?? null);
        $product->setPrice($requestData["price"]?? null);

        $errors = Validate::validateEntity($product,$validator);
        
        if ($errors !== null)
        return $this->json($errors,400);
        
        $entityManager-> persist($product);
        $entityManager->flush();

        return $this->json([
            "product"=>$product->getJson()
        ]);
    }
        
        #[Route('/products/{productId}', name: 'app_product_update',methods:['PATCH'])]
    public function update(EntityManagerInterface $entityManager): JsonResponse
    {
       // dd($entityManager->getRepository(Product::class )->patchById());
        return $this->json([
            'message' => 'Updated',
        ]);
    }

    
    #[Route('/product', name: 'app_product_delete',methods:['DELETE'])]
    public function delete(EntityManagerInterface $entityManager): JsonResponse
    {
       // dd($entityManager->getRepository(Product::class )->delete());
        return $this->json([
            'message' => 'DELETE',
        
        ]);
    }
}

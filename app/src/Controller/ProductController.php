<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
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


    #[Route('/products/{productId}', name: 'app_product_read', requirements: ['id'=>Requirement::DIGITS], methods: ['GET'])]
    public function read(EntityManagerInterface $entityManager, $productId): JsonResponse
    {
        if (!is_numeric($productId))
            return $this->json([
                'error' => 'Product id must be a number']);

       $product = $entityManager->getRepository(Product::class )->findById($productId);

         if(count($product)===0)
            return $this->json([
                'error' => 'Product not found']);

        return $this->json([
            'product' => $product[0]->getJson()
        ]);
    }


    #[Route('/products', name: 'app_product_create',methods:['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request,ValidatorInterface $validator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $errors = Json::getJsonBody($request);

        if ($errors !== null)
            return $this->json($errors,400);

        $product = new Product;
        $product->setName($requestData["name"]?? "");
        $product->setDescription($requestData["description"]?? "");
        $product->setPhoto($requestData["photo"]?? "");
        $product->setPrice(!is_int($requestData["price"])?-1:$requestData["price"]);

        $errors = Validate::validateEntity($product,$validator);

        if ($errors !== null)
        return $this->json($errors,400);

        $entityManager-> persist($product);
        $entityManager->flush();

        return $this->json([
            "product"=>$product->getJson()
        ]);
    }

    #[Route('/products/{productId}', name: 'app_product_update', requirements: ['productId' => Requirement::DIGITS], methods: ['PATCH'])]
    public function update(EntityManagerInterface $entityManager, $productId, Request $request,ValidatorInterface $validator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $errors = Json::getJsonBody($request);

        if ($errors !== null)
            return $this->json($errors, 400);

        if (!is_numeric($productId))
            return $this->json([
                'error' => 'Product id must be a number']);

        $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $productId]);
        if ($product === null)
            return $this->json([
                'error' => 'Product not found']);


        $product->setName($requestData["name"] ?? $product->getName());
        $product->setDescription($requestData["description"] ?? $product->getDescription());
        $product->setPhoto($requestData["photo"] ?? $product->getPhoto());
        $product->setPrice( $requestData["price"] ?? $product->getPrice());

        //validate
        $errors = Validate::validateEntity($product, $validator);
        if ($errors !== null)
            return $this->json($errors, 400);

        $entityManager->flush();


        return $this->json([
            "product" => $product->getJson(),
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

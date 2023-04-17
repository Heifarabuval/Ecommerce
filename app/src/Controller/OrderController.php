<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use PhpParser\JsonDecoder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Utils\Json;
use App\Utils\Validate;

class OrderController extends AbstractController
{
    #[Route('/orders', name: 'app_order_read_all', methods:['GET'])]
    public function readAll(EntityManagerInterface $entityManager): JsonResponse
    {
        $order = $entityManager->getRepository(Order::class)->findAll();
        $arrayorders = array_map(function ($order) {return $order->getJson();}, $order);

        return $this->json([
            "orders" => $arrayorders
        ]);
    }

    #[Route('/orders/{orderId}', name: 'app_order_read_id',requirements: ['id'=>Requirement::DIGITS], methods:['GET'])]
    public function read(EntityManagerInterface $entityManager, $orderId): JsonResponse
    {
        if (!is_numeric($orderId))
            return $this->json([    
                'error' => 'Product id must be a number']);

        $order = $entityManager->getRepository(Order::class )->findById($orderId);

        if(count($order)===0)
            return $this->json([
                'error' => 'Product not found']);
       
        return $this->json([
           'orders' => $order[0]->getJson()
        ]);
    }
}

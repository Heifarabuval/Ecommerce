<?php

namespace App\Controller;

use App\Entity\User;
use App\Utils\Json;
use App\Utils\Validate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user_get', methods: ['GET'])]
    public function get(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json([
            "user"=>$user->getJson()
        ]);
    }


    #[Route('/users', name: 'app_user_update', methods: ['PUT'])]
    public function index(EntityManagerInterface $entityManager,Request $request, ValidatorInterface $validator): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());

        $requestData = json_decode($request->getContent(), true);

        $errors = Json::getJsonBody($request);

        if  ($errors !== null)
            return $this->json($errors,400);

        $user->setEmail($requestData['email'] ?? $user->getEmail());
        $user->setLogin($requestData['login'] ?? $user->getLogin());
        $user->setLastname($requestData['lastname'] ?? $user->getLastname());
        $user->setFirstname($requestData['firstname'] ?? $user->getFirstname());
        $user->setPassword( $user->getPassword());

        $errors = Validate::validateEntity($user, $validator);

        if ($errors !== null)
            return $this->json($errors,400);

        $entityManager->flush();

        return $this->json([
            "user"=>$user->getJson()
        ]);
    }
}

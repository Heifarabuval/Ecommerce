<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Validate\UserRegisterForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthenticationController extends AbstractController
{

    #[Route('/authentication', name: 'app_authentication')]
    public function index(Request $request, ValidatorInterface $validator,EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $userRegisterForm = new User();
        $userRegisterForm->setEmail($requestData['email'] ?? null);
        $userRegisterForm->setLogin($requestData['login'] ?? null);
        $userRegisterForm->setLastname($requestData['lastname'] ?? null);
        $userRegisterForm->setFirstname($requestData['firstname'] ?? null);
        $userRegisterForm->setPassword(strlen($requestData['password']) > 0 ? $passwordHasher->hashPassword($userRegisterForm, $requestData['password']) : "");


        $errors = $validator->validate($userRegisterForm);

        if (count($errors) > 0) {
            $validationErrors = [];

            foreach ($errors as $error) {
                $validationErrors[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json([
                'errors' => $validationErrors,
            ], 400);
        }
        $entityManager->persist($userRegisterForm);
        $entityManager->flush();

        return $this->json([
            "user"=> $userRegisterForm->getJson(),
        ]);
    }
}
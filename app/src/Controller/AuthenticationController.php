<?php

namespace App\Controller;

use App\Entity\User;
use App\Utils\Json;
use App\Utils\Validate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthenticationController extends AbstractController
{

    #[Route('/register', name: 'app_register')]
    public function index(Request $request, ValidatorInterface $validator,EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $errors = Json::getJsonBody($request);

        if  ($errors !== null)
            return $this->json($errors,400);

        $userRegisterForm = new User();
        $userRegisterForm->setEmail($requestData['email'] ?? null);
        $userRegisterForm->setLogin($requestData['login'] ?? null);
        $userRegisterForm->setLastname($requestData['lastname'] ?? null);
        $userRegisterForm->setFirstname($requestData['firstname'] ?? null);
        $userRegisterForm->setPassword(strlen($requestData['password']) > 0 ? $passwordHasher->hashPassword($userRegisterForm, $requestData['password']) : "");

        $errors = Validate::validateEntity($userRegisterForm, $validator);

        if ($errors !== null)
            return $this->json($errors,400);

        if ($entityManager->getRepository(User::class)->findOneBy(['email' => $userRegisterForm->getEmail()]))
            return $this->json(["error" => "Email already exists"],400);

        if ($entityManager->getRepository(User::class)->findOneBy(['login' => $userRegisterForm->getLogin()]))
            return $this->json(["error" => "Login already exists"],400);

        $entityManager->persist($userRegisterForm);
        $entityManager->flush();

        return $this->json([
            "user"=> $userRegisterForm->getJson(),
        ]);
    }
}
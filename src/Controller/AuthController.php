<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class AuthController
{
    private $jwtManager;
    private $passwordEncoder;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->jwtManager = $jwtManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserProviderInterface $userProvider): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];

        $user = $userProvider->loadUserByUsername($email);

        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }

        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
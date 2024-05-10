<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



#[Route('/user')]
class UserController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }
    


    
    public function getUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $jsonData = $this->serializer->serialize($users, 'json');
    
        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }    





    public function addUser(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        if ($data === null) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Invalid JSON data',
            ], Response::HTTP_BAD_REQUEST);
        }
    
        // creation mtaa user jdid
        $user = new User();
        $user->setNom($data['nom'] ?? null);
        $user->setPrenom($data['prenom'] ?? null);
        $user->setEmail($data['email'] ?? null);
        $user->setNumtel($data['numtel'] ?? null);
        $user->setType($data['type'] ?? null);
        $user->setPassword($data['password'] ?? null);



        $errors = $validator->validate($user);
    
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
    
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errorMessages,
            ], Response::HTTP_BAD_REQUEST);
        }
    
        $entityManager->persist($user);
        $entityManager->flush();
    
        return new JsonResponse([
            'status' => 'success',
            'message' => 'User added successfully',
        ], Response::HTTP_CREATED);
    }


    public function show(User $user): JsonResponse
    {
        $jsonData = $this->serializer->serialize($user, 'json');
        
        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }



    public function editUser(Request $request, int $id, EntityManagerInterface $entityManager): JsonResponse
{
    $user = $entityManager->getRepository(User::class)->find($id);

    $data = json_decode($request->getContent(), true);

    if ($data === null) {
        return new JsonResponse([
            'status' => 'error',
            'message' => 'Invalid JSON data',
        ], Response::HTTP_BAD_REQUEST);
    }

    if (isset($data['nom'])) {
        $user->setNom($data['nom']);
    }
    
    if (isset($data['prenom'])) {
        $user->setPrenom($data['prenom']);
    }
    
    if (isset($data['numtel'])) {
        $user->setNumtel($data['numtel']);
    }

    if (isset($data['email'])) {
        $user->setEmail($data['email']);
    }

    if (isset($data['password'])) {
        $user->setPassword($data['password']);
    }

    if (isset($data['type'])) {
        $user->setType($data['type']);
    }

    $errors = $validator->validate($user);

    if (count($errors) > 0) {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $errorMessages,
        ], Response::HTTP_BAD_REQUEST);
    }

    $entityManager->flush();

    return new JsonResponse([
        'status' => 'success',
        'message' => 'User updated successfully',
    ], Response::HTTP_OK);
}






    public function deleteUser(Request $request, int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);
    
        if (!$user) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }
    
        $csrfToken = $request->headers->get('X-CSRF-Token');
        if (!$this->isCsrfTokenValid('delete'.$id, $csrfToken)) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Invalid CSRF token',
            ], Response::HTTP_FORBIDDEN);
        }
    
        $entityManager->remove($user);
        $entityManager->flush();
    
        return new JsonResponse([
            'status' => 'success',
            'message' => 'User deleted successfully',
        ], Response::HTTP_OK);
    }
}

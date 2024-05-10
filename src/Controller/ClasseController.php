<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/classe')]
class ClasseController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ClasseRepository $classeRepository
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->classeRepository = $classeRepository;
    }
    



    public function getClasses(): JsonResponse
    {
        $classes = $this->classeRepository->findAll();
        $jsonData = $this->serializer->serialize($classes, 'json');
    
        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    
    }




    public function addClasse(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        if ($data === null) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Invalid JSON data',
            ], Response::HTTP_BAD_REQUEST);
        }
    
        // creation mtaa classe jdid
        $classe = new Classe();
        $classe->setNom($data['nom'] ?? null);


        $errors = $validator->validate($classe);
    
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
    
        $entityManager->persist($classe);
        $entityManager->flush();
    
        return new JsonResponse([
            'status' => 'success',
            'message' => 'classe added successfully',
        ], Response::HTTP_CREATED);
    }




    public function show(Classe $classe): JsonResponse
    {
        $jsonData = $this->serializer->serialize($classe, 'json');
        
        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }




    public function editClasse(Request $request, Classe $classe, EntityManagerInterface $entityManager): JsonResponse
    {
       
    $classe = $entityManager->getRepository(Classe::class)->find($id);

    $data = json_decode($request->getContent(), true);

    if ($data === null) {
        return new JsonResponse([
            'status' => 'error',
            'message' => 'Invalid JSON data',
        ], Response::HTTP_BAD_REQUEST);
    }

    if (isset($data['nom'])) {
        $classe->setNom($data['nom']);
    }

    $errors = $validator->validate($classe);

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
        'message' => 'classe updated successfully',
    ], Response::HTTP_OK);
}







    public function deleteClasse(Request $request, Classe $classe, EntityManagerInterface $entityManager): JsonResponse
    {
        $classe = $entityManager->getRepository(Classe::class)->find($id);
    
        if (!$classe) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Classe not found',
            ], Response::HTTP_NOT_FOUND);
        }
    
        $csrfToken = $request->headers->get('X-CSRF-Token');
        if (!$this->isCsrfTokenValid('delete'.$id, $csrfToken)) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Invalid CSRF token',
            ], Response::HTTP_FORBIDDEN);
        }
    
        $entityManager->remove($classe);
        $entityManager->flush();
    
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Classe deleted successfully',
        ], Response::HTTP_OK);
    }
}

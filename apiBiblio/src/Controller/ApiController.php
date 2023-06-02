<?php

namespace App\Controller;

use App\Entity\GroupWord;
use App\Entity\Language;
use App\Entity\Word;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\WordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface; 

class ApiController extends AbstractController
{
    #[Route('/api/post', name: 'app_api_index', methods:["GET"])]
    public function index(WordRepository $WordRepository): Response
    {
        /* $postsNormalize = $normalizer->normalize($posts, null, ['groups' => 'post:read']);
        $json = json_encode($postsNormalize);
        $json = $serializer->serialize($posts, 'json', ['groups' => 'post:read']);

        $response = new JsonResponse($json, 200,[],true);*/
        return $this->json($WordRepository->findAll(), 200,[], ['groups' => 'post:read']);
    }

    #[Route('/api/post', name: 'app_api_postStore', methods:["POST"])]
    public function storeWord(Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $jsonRecu = $request->getContent();

        try {
            $posts = $serializer->deserialize($jsonRecu, Word::class, 'json');
        
            $user = new User();
            $user->setEmail('rerezZZZzsss.com');  
            $user->setPassword('testMDP'); 
            $entityManager->persist($user);
            $posts->setUser($user);
    
             
            $groupWord = new GroupWord();
            $groupWord->setLabel('CATEGORieTEST'); 
            $entityManager->persist($groupWord);
            $posts->setGroupWord($groupWord);
    
            $language = new Language();
            $language->setName('Francais'); 
            $entityManager->persist($language);
            $posts->addLanguage($language);
    
            $errors =$validator->validate($posts);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $entityManager->persist($posts);
            $entityManager->flush();
            dd($posts);
            return $this->json($posts, 201,[],['groups'=>'post:read']);
        } catch(NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ],400);
        }
        
    }

    #[Route('/api/user', name: 'app_user', methods:["GET"])]
    public function user(UserRepository $UserRepository): Response
    {
        return $this->json($UserRepository->findAll(), 200,[], ['groups' => 'post:read']);
    }
}

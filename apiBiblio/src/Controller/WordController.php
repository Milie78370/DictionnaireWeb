<?php

namespace App\Controller;

use App\Entity\Word;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Post as RestPost;

class WordController extends AbstractFOSRestController
#AbstractController
{
    #[Route('/word', name: 'app_word')]
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ObjectRepository */
    private $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Word::class);
    }

    /**
    * @Rest\Post(“../api/Word”)
    */
    public function postWord(Request $request): View
    {
        $word = new Word();
        #$word->setInputWord($request->get(‘inputWord’));
        $word->setInputWord($request->get('inputWord'));
        $word->setWordType($request->get('wordType'));
        $word->setLanguage($request->get('language'));

        $this->entityManager->persist($word);
        $this->entityManager->flush();

        return View::create($word, Response::HTTP_CREATED);
    }

    #/**
    #* @Rest\Get(“/words/{id}”)
    # */
    public function getWord(int $id): View
    {
        $word = $this->objectRepository->find($id);

        return View::create($word, Response::HTTP_OK);
    }

    #/**
    # * @Rest\Get(“/books”)
    # */
    public function getWords(): View
    {
        $words = $this->objectRepository->findAll();

        return View::create($words, Response::HTTP_OK);
    }

    #/**
    # * @Rest\Put(“/books/{id}”)
    #*/
    public function putBook(int $id, Request $request): View
    {
        /** @var Word $word */
        $word = $this->objectRepository->find($id);

        $word->setTitle($request->get('inputWord'));

        $this->entityManager->persist($word);
        $this->entityManager->flush();

        return View::create($word, Response::HTTP_OK);
    }

    #/**
    # * @Rest\Delete(“/books”)
    # */
    public function deleteWord(int $id): View
    {
        $this->entityManager->remove($this->objectRepository->find($id));
        $this->entityManager->flush();

        return View::create(Response::HTTP_OK);
    }


    public function index(): Response
    {
        return $this->render('word/index.html.twig', [
            'controller_name' => 'WordController',
        ]);
    }
}

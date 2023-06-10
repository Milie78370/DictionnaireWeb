<?php

namespace App\Controller;

use App\Entity\GroupWord;
use App\Entity\Word;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Language;
use App\Form\WordType;
use App\Repository\GroupWordRepository;
use App\Repository\LanguageRepository;
use App\Repository\WordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/word')]
class WordController extends AbstractController
{
    #[Route('/', name: 'app_word_index', methods: ['GET'])]
    public function index(Request $request, WordRepository $wordRepository, PaginatorInterface $paginator): Response
    {
        $query = $wordRepository->findByWordByUser($this->getUser());
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('word/index.html.twig', [
            'words' => $pagination,
        ]);
    }



    #[Route('/new', name: 'app_word_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WordRepository $wordRepository, EntityManagerInterface $entityManager): Response
    {
        $word = new Word();
        $form = $this->createForm(WordType::class, $word);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $word->setUser($this->getUser());
        
            // Récupérer le champ spécifique du choix de langue + vérification si la langue existe déjà dans la base de données
            $selectedLanguage = $form->get('language')->getViewData();
            $language = $entityManager->getRepository(Language::class)->findOneBy(['id' => $selectedLanguage]);
           
            $word->setLanguage($language);
            $language->addWord($word);
           
            // Récupérer le champ spécifique du choix de groupe word + Vérifier si la catégorie existe déjà dans la base de données
            $selectedgroupWord = $form->get('groupWord')->getViewData();
            $groupWord = $entityManager->getRepository(GroupWord::class)->findOneBy(['id' => $selectedgroupWord]);
           
            $word->setGroupWord($groupWord);
            $groupWord->addWord($word);

            $wordRepository->save($word, true);
            
            $this->addFlash('success', 'Votre mot a été ajouté');
            return $this->redirectToRoute('app_word_index', [], Response::HTTP_SEE_OTHER);
        }

      return $this->renderForm('word/new.html.twig', [
            'word' => $word,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_word_show', methods: ['GET'])]
    public function show(Word $word): Response
    {
        return $this->render('word/show.html.twig', [
            'word' => $word,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_word_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Word $word, WordRepository $wordRepository): Response
    {
        $form = $this->createForm(WordType::class, $word);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wordRepository->save($word, true);

            return $this->redirectToRoute('app_word_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('word/edit.html.twig', [
            'word' => $word,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_word_delete', methods: ['POST'])]
    public function delete(Request $request, Word $word, WordRepository $wordRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$word->getId(), $request->request->get('_token'))) {
            $wordRepository->remove($word, true);
        }

        return $this->redirectToRoute('app_word_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\GroupWord;
use App\Entity\Word;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Language;
use App\Form\WordType;
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
        
            // Récupérer le champ spécifique du choix de langue
            $selectedLanguage = $form->get('language')->getData();
            // Vérifier si la langue existe déjà dans la base de données
            $language = $entityManager->getRepository(Language::class)->findOneBy(['name' => $selectedLanguage]);

            if (!$language) {
                // La langue n'existe pas, créer un nouvel objet Language
                $language = new Language();
                $language->setName($selectedLanguage);
            }
            $word->addLanguage($language);

            $selectedgroupWord = $form->get('groupWord')->getData();
            // Vérifier si la catégorie existe déjà dans la base de données
            $groupWord = $entityManager->getRepository(GroupWord::class)->findOneBy(['label' => $selectedgroupWord]);

            if (!$groupWord) {
                // La catégorie n'existe pas, créer un nouvel objet GroupWord
                $groupWord = new GroupWord();
                $groupWord->setLabel($selectedgroupWord);
            }
            $word->setGroupWord($groupWord);

            if(!$language && !$groupWord){
                $language->setWord($word);
                $language->setGroupWord($groupWord);
                $entityManager->persist($language);
                $entityManager->persist($groupWord);
            }

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

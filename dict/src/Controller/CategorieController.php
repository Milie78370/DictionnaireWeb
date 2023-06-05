<?php

namespace App\Controller;

use App\Entity\GroupWord;
use App\Form\CategorieType;
use App\Repository\GroupWordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(GroupWordRepository $groupWordRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'words' => $groupWordRepository->findAll(),
        ]);
    }

    #[Route('/newCategorie', name: 'newcategorie', methods: ['GET', 'POST'])]
    public function newCategorie(Request $request, GroupWordRepository $groupWordRepository): Response
    {
        $word = new GroupWord();
        $form = $this->createForm(CategorieType::class, $word);
        $form->handleRequest($request);

        if ($form ->isSubmitted() && $form ->isValid()) {
            // Récupérer le champ spécifique du choix de langue
            $categorie = $form ->get('label')->getData();
            $word->setLabel($categorie);
        
            $groupWordRepository->save($word, true);
            $this->addFlash('success', 'Votre mot a été ajouté');
            return $this->redirectToRoute('app_word_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/index.html.twig', [
            'word' => $word,
            'form' => $form,
        ]);
    }
}

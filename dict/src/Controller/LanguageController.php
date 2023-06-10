<?php

namespace App\Controller;
use App\Entity\Language;
use App\Entity\Word;
use App\Form\LanguageType;
use App\Repository\LanguageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class LanguageController extends AbstractController
{

    #[Route('/language', name: 'language')]
    public function index(LanguageRepository $languageRepository): Response
    {
        return $this->render('language/index.html.twig', [
            'langue' => $languageRepository->findAll(),
        ]);
    }

    #[Route('/newLanguage', name: 'newLanguage', methods: ['GET', 'POST'])]
    public function newLanguage(Request $request, LanguageRepository $languageRepository): Response
    {
        $langue = new Language();
        $form = $this->createForm(LanguageType::class, $langue);
        $form->handleRequest($request);

        if ($form ->isSubmitted() && $form ->isValid()) {
            // Récupérer le champ spécifique du choix de langue
            $language = $form->get('name')->getData();
            $langue->setName($language);
        
            $languageRepository->save($langue, true);
            $this->addFlash('success', 'Votre mot a été ajouté');
            return $this->redirectToRoute('app_word_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('language/index.html.twig', [
            'langue' => $langue,
            'form' => $form,
        ]);
    }

}

<?php

namespace App\Controller;

use App\Entity\Traduction;
use App\Repository\TraductionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Word;
use App\Form\TraductionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Language;

class TraductionController extends AbstractController
{

    #[Route('/traduction', name: 'app_traduction', methods: ['GET'])]
    public function index(Request $request, TraductionRepository $traductionRepository, PaginatorInterface $paginator): Response
    {
        $query = $traductionRepository->findTraduction();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('traduction/index.html.twig', [
            'words' => $pagination,
        ]);
    }


    #[Route('/newTrad', name: 'tradWord', methods: ['GET', 'POST'])]
    public function new(Request $request, TraductionRepository $traductionRepository, EntityManagerInterface $entityManager): Response
    {
        $trad = new Traduction();
        $form = $this->createForm(TraductionType::class, $trad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupérer le champ spécifique du choix de langue + vérification si la langue existe déjà dans la base de données
            $selectedLanguage = $form->get('language')->getViewData();
            $language = $entityManager->getRepository(Language::class)->findOneBy(['id' => $selectedLanguage]);

            $trad->setLanguageTrad($language);
            $language->addTraduction($trad);

            // Récupérer le champ spécifique du choix de groupe word + Vérifier si la catégorie existe déjà dans la base de données
            $selectedgroupWord = $form->get('wordLink')->getViewData();
            $word = $entityManager->getRepository(Word::class)->findOneBy(['id' => $selectedgroupWord]);

            $trad->addWordLink($word);
            $word->setTraduction($trad);

            $traductionRepository->save($trad, true);

            $this->addFlash('success', 'Votre mot de traduction a été ajouté');
            return $this->redirectToRoute('app_word_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('traduction/form.html.twig', [
            'trad' => $trad,
            'form' => $form,
        ]);
    }

}

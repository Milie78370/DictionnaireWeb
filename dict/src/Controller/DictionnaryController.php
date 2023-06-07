<?php

namespace App\Controller;

use App\Form\FormSearch;
use App\Entity\Word;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WordRepository;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class DictionnaryController extends AbstractController
{
    #[Route('/dictionnary', name: 'app_dictionnary', methods: ['GET'])]
    public function index(Request $request, WordRepository $wordRepository, PaginatorInterface $paginator): Response
    {
        $query = $wordRepository->findAll();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('dictionnary/index.html.twig', [
            'words' => $pagination,
        ]);
    }

    #[Route('/dictSearch', name: 'search', methods: ['GET', 'POST'])]
    public function SearchWord(Request $request, WordRepository $wordRepository): Response
    {
        
        $word = new Word();
        $form = $this->createForm(FormSearch::class, $word);
        $form->handleRequest($request);

        $result='';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get('def')->getData();
            $dataCategorie = $form->get('groupWord')->getData();
            

            if(!empty($data)){
                // Cas où seulement le champ de recherche est rempli
                $result = $wordRepository->findWord($data);
            } else if(!empty($data) && !empty($dataCategorie) ) {
                // Cas où les deux champs sont remplis
                $result = $wordRepository->findByGroupByUserCategorie($data, $dataCategorie);
            } else if(!empty($dataCategorie)){
                // Cas où seulement le champ de catégorie est rempli
                $result = $wordRepository->findByGroupByUser($dataCategorie);
            } else {
                // Cas où aucun champ n'est rempli
                $result = $wordRepository->findAll();
            }

        }


        return $this->render('dictionnary/newCategorie.html.twig', [
            'form' => $form,
            'words' => $result,
        ]);
    }



}
<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ServiceController extends AbstractController
{

    private $apiClientWord;
    private $apiClientLanguage;

    public function __construct(HttpClientInterface $apiClientWord, HttpClientInterface $apiClientLanguage)
    {
        $this->apiClientWord = $apiClientWord;
        $this->apiClientLanguage = $apiClientLanguage;

    }


    private function downloadDictionary(): ?string
    {
        $responseWord = $this->apiClientWord->request('GET', 'http://127.0.0.1:8000/api/words');


        if ($responseWord->getStatusCode() === 200 ) {
            return $responseWord->getContent();
        }

        return null;
    }

    private function DownloadLanguageName($languageId): ?string
    {
        $responseLanguage = $this->apiClientLanguage->request('GET', 'http://127.0.0.1:8000/api/languages/'.$languageId);


        if ($responseLanguage->getStatusCode() === 200 ) {
            $languageName = json_decode($responseLanguage->getContent(), true);
            return $languageName['name'];

        }

        return null;
    }



    #[Route('/randomWordService', name:'randomWord', methods:['GET'])]
    public function getRandomWord(): Response
    {
        $dictionary = $this->downloadDictionary();
        $wordsArray = json_decode($dictionary, true);

        if (is_array($wordsArray) && count($wordsArray) > 0) {
            $randomKey = array_rand($wordsArray['hydra:member']);
            $randomWord = $wordsArray['hydra:member'][$randomKey];

            $definition = $randomWord['def'];
            $inputWord = $randomWord['inputWord'];
            $wordType = $randomWord['wordType'];
            $languageId = $randomWord['language'];

            $regexp = '/\/api\/languages\/(\d+)/';
            preg_match($regexp, $languageId, $matches);
            $id = $matches[1];

            $languageName = $this->DownloadLanguageName($id);

            $outputField = "Mot : $inputWord<br>";
            $outputField .= "Définition : $definition<br>";
            $outputField .= "Type du mot : $wordType<br>";
            //$outputField .= "Langue : $languageId<br>";
            $outputField .= "Langue : $languageName<br>";
        } else {
            $outputField = 'No word found';
        }


        return new Response($outputField, 200, ['Content-Type' => 'text/html']);
    }

    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
}

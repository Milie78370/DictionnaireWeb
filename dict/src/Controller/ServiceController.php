<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

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

    private function getMotspecifique($motId): ?string
    {
        $responseWord = $this->apiClientWord->request('GET', 'http://127.0.0.1:8000/api/words/'.$motId);


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

    private function GetTraductionWord($traductionId): ?string
    {
        $responseLanguage = $this->apiClientLanguage->request('GET', 'http://127.0.0.1:8000/api/traductions/'.$traductionId);


        if ($responseLanguage->getStatusCode() === 200 ) {
            return $responseLanguage->getContent();
        }

        return null;
    }

    function extractIdUrl($regexp, $text) {
        if (preg_match($regexp, $text, $matches)) {
            if (isset($matches[1])) {
                return $matches[1];
            }
        }
        // Retourne null si aucun ID n'est trouvé
        return null;
    }

    #[Route('/showWord', name:'showWord', methods:['GET'])]
    public function showWord(Request $request): Response
    {
        // récuperation de id du mot
        $wordId = $request->query->get('id');

        // partie affichage du mot spécifique à partir de l'url de l'api
        $dictionary = $this->getMotspecifique($wordId);
        $wordsArray = json_decode($dictionary, true);

        if (is_array($wordsArray) && count($wordsArray) > 0) {
            $definition = $wordsArray['def'];
            $inputWord = $wordsArray['inputWord'];
            $wordType = $wordsArray['wordType'];
            $languageId = $wordsArray['language'];
            $traductionId = $wordsArray['traductions'];

            $regexpL = '/\/api\/languages\/(\d+)/';
            $idL = $this->extractIdUrl($regexpL, $languageId);


            $ids = [];
            foreach ($traductionId as $traduction) {
                $regexpT = '/\/api\/traductions\/(\d+)/';
                $idT =  $this->extractIdUrl($regexpT, $traduction);
                $ids[] = $idT;

            }

            $languageName = $this->DownloadLanguageName($idL);

            $outputField = "Mot : $inputWord<br>";
            $outputField .= "Définition : $definition<br>";
            $outputField .= "Type du mot : $wordType<br>";
            $outputField .= "Langue : $languageName<br>";


            foreach ($ids as $id) {
                $traductionName = $this->GetTraductionWord($id);
                $data = json_decode($traductionName);
                $outputField .= "traduction du word : $data->translatedWord.<br>";
                $outputField .= "traduction definition : $data->def.<br>";

                $idLTrad = $this->extractIdUrl($regexpL, $data->languageTrad);
                $languageTrad = $this->DownloadLanguageName($idLTrad);
                $outputField .= "traduction langue : $languageTrad.<br>";
            }

        } else {
            $outputField = 'No word found';
        }


        return new Response($outputField, 200, ['Content-Type' => 'text/html']);
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

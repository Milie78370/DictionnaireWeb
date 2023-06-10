<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;


class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'http://127.0.0.1:8000/api', // URL API
            'timeout'  => 5,
        ]);
    }

    public function getData()
    {
        $response = $this->httpClient->request('GET', '/api/words');

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            return $data;
        }

        return null;
    }
    #[Route('/dictDownload', name:'downloadJson', methods:['GET'])]
    public function downloadDataAsJson()
    {
        $data = $this->getData();

        if ($data !== null) {
            $jsonData = json_encode($data);

            // téléchargement en local dans le dossier de téléchargement du bureau
            $filePath = $_SERVER['HOME'] . '/Downloads/dataDict.json';

            file_put_contents($filePath, $jsonData);

            return $this->redirectToRoute('app_dictionnary');
        }
    }

    public function index(): Response
    {
        $data = $this->getData();
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, HttpClientInterface $client): Response
    {
        // Déclaration de variables
        $gameContent = [];
        $api_key = $this->getParameter('app.rawg_io_api_key'); // renvoie vers la fichier service afin de récupérer la clé API et donc ne pas l'avoir en claire dans mon code

        // Création du formulaire de recherche de jeu
        $form = $this->createFormBuilder()
        ->add('searchbar', TextType::class)
        ->add('search', SubmitType::class, ['label' => 'Search'])
        ->getForm();

        // Soumission du formulaire et réponse de l'API rawg.io
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchBarScan = $form->getData();
            $response = $client->request(
                'GET',
                "https://api.rawg.io/api/games?key=$api_key&search=" . $searchBarScan['searchbar']
            );
            $gameContent = $response->toArray()['results'];
        }
        // Renvoie vers la vue de la page principale de recherche de jeux-video
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'searchForm' => $form->createView(),
            'videoGames' =>  $gameContent
        ]);
    }
}

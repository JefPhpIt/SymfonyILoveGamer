<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\HttpClient\HttpClientInterface;


use App\Helper\GamesApiHelper;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'app_home')]
    // #[IsGranted('ROLE_USER', statusCode: 401)]
    public function index(Request $request, HttpClientInterface $client): Response
    {
        if ($this->isGranted('ROLE_USER') == false) {
            return $this->redirectToRoute('app_login');
        }

        // dd( $user = $this->getUser());

        $gameResults = [];


        $form = $this->createFormBuilder()
            ->add('game', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $gameSearch = $data["game"];
            $gameResults = $this->searchGames($gameSearch, $client);
        }

        return $this->render('home.html.twig', ["form" => $form, "games" => $gameResults]);
    }


    #[Route('/add-game/{id}', name: 'add_game')]
    public function addGame($id, HttpClientInterface $client): Response
    {
        $this->getGameById($id, $client);   
    }

    private function getGameById(int $id, HttpClientInterface $client)
    {
        $key = $this->getParameter('app.apikey');

        $url = "https://api.rawg.io/api/games/$id?key=$key";

        $response = $client->request('GET', $url);
        $gameDetails = $response->toArray();

        $newGameObject = new Game();
        $newGameObject->setIdRawgAPI($gameDetails["id"]);
        $newGameObject->setName($gameDetails["name"]);
        $newGameObject->setImagePath($gameDetails["background_image"]);
       


      
        return $newGameObject;
    }


    private function searchGames(string $game,  HttpClientInterface $client)
    {
        $key = $this->getParameter('app.apikey');

        $url = "https://api.rawg.io/api/games?key=$key&search=$game";

        $response = $client->request('GET', $url);
        $parsedResponse = $response->toArray();

        $results = [];
        foreach ($parsedResponse['results'] as $game) {
            $newGameObject = new Game();
            $newGameObject->setIdRawgAPI($game["id"]);
            $newGameObject->setName($game["name"]);
            $newGameObject->setImagePath($game["background_image"]);
            $results[] = $newGameObject;
        }

        return $results;
    }
}

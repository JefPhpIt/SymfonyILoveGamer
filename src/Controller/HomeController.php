<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, HttpClientInterface $client): Response
    {
        if ($this->isGranted('ROLE_USER') == false) {
            return $this->redirectToRoute('app_login');
        }

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

        return $this->render('games/index.html.twig', ["form" => $form, "games" => $gameResults]);
    }


    #[Route('/user-games', name: 'user_games')]
    public function getFavoriteGame(): Response
    {
        if ($this->isGranted('ROLE_USER') == false) {
            return $this->redirectToRoute('app_login');
        }

        $games =  $this->getUser()->getGames();
        return $this->render('games/user_games.html.twig', ["games" => $games]);
    }


    #[Route('/add-game/{id}', name: 'add_game')]
    public function addGame($id, HttpClientInterface $client, EntityManagerInterface  $entityManager): Response
    {
        if ($this->isGranted('ROLE_USER') == false) {
            return $this->redirectToRoute('app_login');
        }

        // check if game already exists in db
        $game = $entityManager->getRepository(Game::class)->findOneBy(['idRawgAPI' => $id]);

        // game does not already exist in db
        // get the details game from api
        if(! $game)
        {
            $game = $this->getGameFromApi($id, $client);
            $entityManager->persist($game);    
        }
        
        $user = $this->getUser();

        $user->addGame($game);
        $entityManager->flush();

        return $this->redirectToRoute('user_games');
    }


    #[Route('/remove-game/{id}', name: 'remove_game_favorites')]
    public function removeGameFromFavorite($id, EntityManagerInterface  $entityManager): Response
    {
        $game = $entityManager->getRepository(Game::class)->find($id);

        $user = $this->getUser();
        $user->removeGame($game);
        
        $entityManager->flush();
        
        return $this->redirectToRoute('user_games');
    }

    
    #[Route('/details-game/{id}', name: 'details_game')]
    public function displayDetailsGame($id, EntityManagerInterface  $entityManager, HttpClientInterface $client): Response
    {
        // check if game already exists in db
        $game = $entityManager->getRepository(Game::class)->findOneBy(['idRawgAPI' => $id]);
        
        
        // game dont exists in db
        if(! $game)
        {
            $game = $this->getGameFromApi($id, $client);
            $entityManager->persist($game);    
        }
        
        
        // check if the user has already the game in his favorites
        $user = $this->getUser();
        $userHasGame = $user->hasGame($game);

        return $this->render('games/details.html.twig', ["game" => $game, "userHasGame" => $userHasGame]);
    }



    // get a single games from the api by its id
    private function getGameFromApi(int $id, HttpClientInterface $client): Game
    {
        $key = $this->getParameter('app.apikey');
        $apiUrl = $this->getParameter('app.apiUrl');

        $url = "$apiUrl/$id?key=$key";

        $response = $client->request('GET', $url);
        $gameDetails = $response->toArray();

        $newGameObject = new Game();
        $newGameObject->setIdRawgAPI($gameDetails["id"]);
        $newGameObject->setName($gameDetails["name"]);
        $newGameObject->setImagePath($gameDetails["background_image"]);
        $newGameObject->setReleased(new DateTime($gameDetails["released"]));
        $newGameObject->setDescription($gameDetails["description"]);

        return $newGameObject;
    }


    // get search results from the api 
    private function searchGames(string $game,  HttpClientInterface $client): array
    {
        $key = $this->getParameter('app.apikey');
        $apiUrl = $this->getParameter('app.apiUrl');

        $url = "$apiUrl?key=$key&search=$game";

        $response = $client->request('GET', $url);
        $parsedResponse = $response->toArray();

        $results = [];
        $user = $this->getUser();

        foreach ($parsedResponse['results'] as $game) {
            $newGameObject = new Game();
            $newGameObject->setIdRawgAPI($game["id"]);
            $newGameObject->setName($game["name"]);
            $newGameObject->setImagePath($game["background_image"]);

            $newGameObject->setHasUser($user->hasGame($newGameObject));
           
            $results[] = $newGameObject;
        }

        return $results;
    }
}

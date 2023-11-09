<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use LDAP\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
//use Symfony\Component\Security\Core\Security;


use App\Entity\Game;
use App\Entity\User;
use App\Entity\UserGame;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(Request $request, HttpClientInterface $client, ParameterBagInterface $params, UserInterface $user): Response
    {
        $form = $this->createFormBuilder()
            ->add('game', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Search'])
            ->getForm();

        $form->handleRequest($request);

        // Récupérer l'utilisateur depuis la base de données
        $userId = $user->getId();

        $results = [];

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            $gameSearch = $form->getData()['game'];
            $apiKey = $this->getParameter('app.api_rawgio_key');
            $response = $client->request("GET", "https://api.rawg.io/api/games?key=$apiKey&search=$gameSearch");
            $results = $response->toArray()['results'];
            // dd($results);
        }

        return $this->render('game/index.html.twig', [
            "form" => $form->createView(),
            "results" => $results,
            'user' => $user,
            'userId' => $userId,

        ]);
    }
    #[Route('/addgame/{id}', name: 'add_game')]
    public function addGame($id, Request $request, HttpClientInterface $client, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $apiKey = $this->getParameter('app.api_rawgio_key');
        $response = $client->request("GET", "https://api.rawg.io/api/games/$id?key=$apiKey");
        $results = $response->toArray();
        
        
        //Inscriiption dans la table game
        $game=new Game();

        $game->setName($results['name']); 
        $game->setImagePath($results['background_image']);
        $game->setDescription($results['description']);
        $game->setIdrawgapi($results['id']);
        
       $entityManager->persist($game);
       $entityManager->flush();

        //Inscription dans la table user_game
        $userGame=new UserGame();
        
        //$userGame->setId(NULL);
        // Obtenez l'utilisateur actuellement connecté

        $userGame->setUserId($user->getId());
        $userGame->setGameId($game->getId());        
        
        $entityManager->persist($userGame);
        $entityManager->flush();         
       
        return $this->redirectToRoute("app_game");
    }

    #[Route('/list/game/user/{id}', name: 'list_game')]
    public function listGamesForUser($id, Request $request, EntityManagerInterface $entityManager): Response
    {

        /*$form = $this->createFormBuilder()
            ->add('game', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Search'])
            ->getForm();

        $form->handleRequest($request);*/

        // Récupérer l'utilisateur depuis la base de données
        $repository = $entityManager->getRepository(User::class);

        // look for a single Product by its primary key (usually "id")
        $user = $repository->find($id);
       // $user = $user->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Récupérer la liste des jeux pour cet utilisateur
        $games = $user->getGames(); // Supposons qu'il y ait une méthode "getGames()" dans l'entité User

        // Renvoyer les jeux à un template pour affichage
        return $this->render('game/list.html.twig', [
            //"form" => $form->createView(),
            'user' => $user,
            'games' => $games,
        ]);
    }
}

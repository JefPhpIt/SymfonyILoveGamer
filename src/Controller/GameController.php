<?php

namespace App\Controller;

use App\Entity\Game;
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

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(Request $request, HttpClientInterface $client, ParameterBagInterface $params): Response
    {
        $form = $this->createFormBuilder()
            ->add('game', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Search'])
            ->getForm();

        $form->handleRequest($request);

        $results = [];

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            $gameSearch = $form->getData()['game'];
            $apiKey = $this->getParameter('app.api_rawg_key');
            $response = $client->request("GET", "https://api.rawg.io/api/games?key=$apiKey&search=$gameSearch");
            $results = $response->toArray()['results'];
            // dd($results);
        }


        return $this->render('game/index.html.twig', [
            "form" => $form->createView(),
            "results" => $results,

        ]);
    }
    #[Route('/addgame/{id}', name: 'add_game')]
    public function addGame($id, Request $request, HttpClientInterface $client, EntityManagerInterface $entityManager): Response
    {
        $apiKey = $this->getParameter('app.api_rawg_key');
        $response = $client->request("GET", "https://api.rawg.io/api/games/$id?key=$apiKey");
        $results = $response->toArray();
       
     

       
        $game=new Game();

        $game->setName($results['name']);

        
        
        $game->setImagePath($results['background_image']);

        

        $game->setDescription($results['description']);
        $game->setIdrawgapi($results['id']);


        $entityManager->persist($game);
        
       
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

       
       
       
            return $this->redirectToRoute("app_game");

    }
}

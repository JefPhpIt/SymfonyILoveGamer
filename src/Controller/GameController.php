<?php

namespace App\Controller;


use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GameController extends AbstractController
{
    #[Route('/', name: 'app_game')]
    public function index(Request $request, HttpClientInterface $client): Response
    {
      

        $form = $this->createFormBuilder()
            ->add('game', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Chercher'])
            ->getForm();

            $form->handleRequest($request);

            $results = [];

            if($form->isSubmitted() && $form->isValid())
            {
                $search  = $form->getData()["game"];

                $apiKey = $this->getParameter("app.api_rawg_key");

                $response = $client->request(
                    'GET',
                    "https://api.rawg.io/api/games?key=$apiKey&search=$search"
                );

                $results = $response->toArray()["results"];
            }

        return $this->render('game/index.html.twig', [
            "form" => $form->createView(),
            "gamesResults" => $results 
        ]);
    }
    #[Route('/addgame/{id}', name: 'ajouter_favoris')]
    public function addGame($id, Request $request, HttpClientInterface $client, EntityManagerInterface $entityManager): Response
    {
        $apiKey = $this->getParameter("app.api_rawg_key");
        
        $response = $client->request(
            'GET',
            "https://api.rawg.io/api/games/$id?key=$apiKey"
        );

        $gameDetails = $response->toArray();
        // dd($results);

        $newGame = new Game();

        $newGame->setName($gameDetails["name"]);
        $newGame->setImage($gameDetails["background_image"]);


        $user = $this->getUser();

        $user->addGame($newGame);

        $entityManager->persist($newGame);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();


        return $this->redirectToRoute("app_game");

    }
}

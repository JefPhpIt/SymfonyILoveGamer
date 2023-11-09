<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class VideoGameController extends AbstractController
{
    #[Route('/video/game', name: 'app_video_game')]
   
   
        
    public function index(Request $request, HttpClientInterface $client): Response
    
{
    //$form = $this->createForm(SearchType::class);
   
    $search = $request->get('games_seached');
   
    if ($search !="")
    //dd($request);
    {
        //$search = $form->getData();
        $response = $client->request(
            'GET',
            "https://api.rawg.io/api/games?key=" .'9a6323ee0e834af1845fd5e95121d5e2' . "&search=" . $search);
    }
    //dd ($response->toarray());
    $results = $response->toarray();
    //dd($results);
    return $this->render('video_game/index.html.twig', [
        'controller_name' => 'VideoGameController',
       'videoGames'=> $results['results']
    ]);

   

}

    }
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;



class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Request $request, HttpClientInterface $client, ParameterBagInterface $params): Response
    {   
        
        $form = $this->createFormBuilder()
            ->add('game', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Search'])
            ->getForm();
        
        //$form->handleRequest($request);        
       
        $form->handleRequest($request);

        //Méthode POST
        if ($request->isMethod('POST')) {

            //$form->submit($request->request->get($form->getName()));    
            if ($form->isSubmitted() && $form->isValid()) {
                // perform some action...
                
                $results = [];
                
                $gameSearch = $form->getData()['game'];
                $apiKey     = $this->getParameter('app.api_game_key');
                $response   = $client->request("GET", "https://api.rawg.io/api/games?key=$apiKey&search=$gameSearch");
                  
                $results = $response->toArray()['results'];

                //dd($gameSearch);                

                /*$gameSearch = $form->getData()['game'];
                $apiKey = $this->getParameter('app.api_rawg_key');
                $response = $client->request("GET", "https://api.rawg.io/api/games?key=$apiKey&search=$gameSearch");
                $results = $response->toArray()['results'];*/
                
    
                //return $this->redirectToRoute('task_success');
                // Récupérer des données pour le tableau de bord
                return $this->render('dashboard/index.html.twig', [            
                    'form' => $form,
                    "results"=> $results,
                ]);
            }
        }     


        
    }    
}

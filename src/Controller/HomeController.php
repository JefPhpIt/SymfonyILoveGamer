<?php

namespace App\Controller;

use App\Entity\Games;
use App\Form\SearchGameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request): Response
    {
        // $game= new Games();
        $form = $this->createForm(SearchGameType::class,);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search_result = ($request->request->all()['search_game']['gameName']);
            // dd($search_result);
        }
        return $this->render('home/index.html.twig', [
           'form'=> $form->createView()
        ]);
    }
}

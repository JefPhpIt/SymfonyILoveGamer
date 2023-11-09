<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HomeControlerController extends AbstractController
{
    #[Route('/', name: 'app_home_controler')]
    public function index(): Response
    {
         
        /*$form = $this->createFormBuilder()
            ->add('game', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Search'])
            ->getForm();*/

        return $this->render('home_controler/index.html.twig', [
            'controller_name' => 'HomeControlerController',
            //"form" => $form
        ]);
    }
}

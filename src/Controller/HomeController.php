<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[IsGranted('ROLE_USER', statusCode: 423)]
    public function index(): Response
    {
        $key = $this->getParameter('app.apikey');

        $url = "https://api.rawg.io/api/games?key=$key&search=call";

        // dd($url);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $rawResponse = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        // dd($info);

        if ($info['http_code'] === 200) {
             $response = json_decode($rawResponse, true);
             $games = $response['results'];
             dd($games);
        }

        $form = $this->createFormBuilder()
            ->add('game', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        return $this->render('home.html.twig', ["form" => $form]);
    }
}

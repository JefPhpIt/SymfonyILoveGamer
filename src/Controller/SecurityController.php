<?php

namespace App\Controller;

use App\Entity\VideoGame;
use App\Repository\VideoGameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
//     #[Route(path: '/apijson', name: 'apijson')]

//     public function GameListjson(VideoGameRepository $videoGameRepository)
// {
//     $videoGames = $videoGameRepository->findAll();
//     return new JsonResponse(array_map(
//         function(VideoGame $videoGame) {
//             return [
//                 'id' => $videoGame->getId(),
//                 'name' => $videoGame->getName()
//             ];
//         },
//         $videoGames
//     ));
// }

// #[Route(path: '/apixml', name: 'apixml')]

// public function GameListxml(VideoGameRepository $videoGameRepository)
// {
// $videoGames = $videoGameRepository->findAll();
// return new JsonResponse(array_map(
//     function(VideoGame $videoGame) {
//         return [
//             'id' => $videoGame->getId(),
//             'name' => $videoGame->getName()
//         ];
//     },
//     $videoGames
// ));
// }

// #[Route(path: '/apicsv', name: 'apicsv')]

// public function GameListcsv(VideoGameRepository $videoGameRepository)
// {
// $videoGames = $videoGameRepository->findAll();
// return new JsonResponse(array_map(
//     function(VideoGame $videoGame) {
//         return [
//             'id' => $videoGame->getId(),
//             'name' => $videoGame->getName()
//         ];
//     },
//     $videoGames
// ));
// }
}




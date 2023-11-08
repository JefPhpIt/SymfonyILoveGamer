<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\VideoGameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    #[Route('/favorite', name: 'app_favorite', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, VideoGameRepository $videoGameRepository): Response
    {
        $user = $this->getUser();
        // force user to login page if not connected
        if(!$user)
        {
            return $this->redirectToRoute('app_login');
        }

        $id = $request->query->get('id');
        if($id) 
        {
            $this->removeFavorite($id, $entityManager, $videoGameRepository, $userRepository);
        }

        $email = $user->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $email]);

        $favorites = $user->getIdVideoGame();
        $favorites = $favorites->toArray();
        // dd($favorites);
        
        return $this->render('favorite/index.html.twig', [
            'email' => $email,
            'games' => $favorites
        ]);
    }

    // TODO make it work
    public function removeFavorite(int $id, EntityManagerInterface $entityManager, VideoGameRepository $videoGameRepository, UserRepository $userRepository): bool
    {
        // check if game isnt already in database
        $game = $videoGameRepository->findOneBy(['id_API' => $id]);
        // dd($game);

        if(is_null($game))
        {
            return false;         
        }

        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $user->removeIdVideogame($game);

        $entityManager->flush();

        return true;
    }
}

<?php

namespace App\Controller;

use App\Entity\Platform;
use App\Entity\VideoGame;
use App\Form\SearchType;
use App\Repository\PlatformRepository;
use App\Repository\UserRepository;
use App\Repository\VideoGameRepository;
use App\Service\VideoGameService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('//video-game')]
#[IsGranted('ROLE_USER')]
class VideoGameController extends AbstractController
{
    #[Route('/', name: 'app_video_game')]
    public function index(): Response
    {
        $search = $this->createForm(SearchType::class);
        return $this->render('video_game/index.html.twig', [
            'user' => $this->getUser(),
            'search' => $search
        ]);
    }

    #[Route('/search', name: 'app_video_game_search', methods: ['POST'])]
    public function search(Request $request, VideoGameService $videoGameService)
    {
        $search = $request->request->all()['search']['search'];
        $games = $videoGameService->searchByName($search);

        return $this->render('video_game/_search.html.twig', [
            'games' => $games['results']
        ]);

    }

    #[Route('/add-video-game/{id}', name: 'app_video_game_add', methods: ['POST'])]
    public function add(VideoGameService $videoGameService, VideoGameRepository $videoGameRepository,
                        EntityManagerInterface $em, UserRepository $userRepository,
                        PlatformRepository $platformRepository, int $id)
    {

        $game = $videoGameService->searchById($id);
        $gameExit = $videoGameRepository->findOneBy(['apiId' => $id]);
        $platformMane = $game['platforms'][0]['platform']['name'];
        $platformExit = $platformRepository->findOneBy(['name' => $platformMane]);
        // if video game exit in database set video game at user
        if (!$gameExit) {
            //If platform not exit
            if (!$platformExit) {
                $platform = new Platform();
                $platform->setName($platformMane);
            } else {
                $platform = $platformExit;
            }
            $videoGame = new VideoGame();
            $videoGame
                ->setName($game['name'])
                ->setImgUrl($game['background_image'])
                ->setRating($game['rating'])
                ->setReleased(new \DateTime($game['released']))
                ->setApiId($game['id'])
                ->addPlatfomr($platform);
        } else {
            $videoGame = $gameExit;
        }

        $user = $this->getUser();
        // Check if the user already has the game
        $userAddVideoGame = $userRepository->findVideoGameByUser($user, $id);
        if (empty($userAddVideoGame)) {
            $user->addVideoGame($videoGame);
            $em->persist($user);
            $em->flush();
        }

        return $this->render('video_game/_listVideoGames.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/delete/{id}', name: 'app_video_game_delete', methods: ['POST'])]
    public function delete(VideoGame $videoGame, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $user->removeVideoGame($videoGame);
        $em->flush();

        return $this->render('video_game/_listVideoGames.html.twig', [
            'user' => $user
        ]);
    }
}

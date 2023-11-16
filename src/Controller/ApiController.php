<?php

namespace App\Controller;

use App\Entity\VideoGame;
use App\Repository\VideoGameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class ApiController extends AbstractController
{
    #[Route(path: '/apijson', name: 'apijson')]

    public function GameListjson(VideoGameRepository $videoGameRepository)
{
    $videoGames = $videoGameRepository->findAll();
    return new JsonResponse(array_map(
        function(VideoGame $videoGame) {
            return [
                'id' => $videoGame->getId(),
                'name' => $videoGame->getName()
            ];
        },
        $videoGames
    ));
}

#[Route(path: '/apixml', name: 'apixml')]

public function GameListxml(VideoGameRepository $videoGameRepository)
{
$videoGames = $videoGameRepository->findAll();
return new JsonResponse(array_map(
    function(VideoGame $videoGame) {
        return [
            'id' => $videoGame->getId(),
            'name' => $videoGame->getName()
        ];
    },
    $videoGames
));
}

#[Route(path: '/apicsv', name: 'apicsv')]

public function GameListcsv(VideoGameRepository $videoGameRepository)
{
$videoGames = $videoGameRepository->findAll();
 return new CsvEncoder(array_map(
    function(VideoGame $videoGame) {
        return [
            'id' => $videoGame->getId(),
            'name' => $videoGame->getName()
        ];
    },
    $videoGames
));
// return new JsonResponse(array_map(
//     function(VideoGame $videoGame) {
//         return [
//             'id' => $videoGame->getId(),
//             'name' => $videoGame->getName()
//         ];
//     },
//     $videoGames
// ));
}
}

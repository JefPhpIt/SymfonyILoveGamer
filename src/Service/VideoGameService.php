<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class VideoGameService extends AbstractController implements videoGameServiceInterface
{
    public $apiRawg;
    public function __construct(public HttpClientInterface $client, ParameterBagInterface $parameterBag)
    {
        $this->apiRawg = $parameterBag->get('API_RAWG');
    }

    public function searchByName(string $name): array
    {
        $response = $this->client->request(
            'GET',
            "https://api.rawg.io/api/games?key=" . $this->apiRawg . "&search=" . $name
        );

        return $response->toArray();
    }

    public function searchById(int $id): array
    {
        $response = $this->client->request(
            'GET',
            "https://api.rawg.io/api/games/" . $id ."?key=" . $this->apiRawg
        );
        return $response->toArray();
    }

}
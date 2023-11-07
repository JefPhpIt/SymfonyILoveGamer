<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idRawgAPI = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRawgAPI(): ?int
    {
        return $this->idRawgAPI;
    }

    public function setIdRawgAPI(int $idRawgAPI): static
    {
        $this->idRawgAPI = $idRawgAPI;

        return $this;
    }
}

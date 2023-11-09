<?php

namespace App\Entity;

use App\Repository\VideoGamesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGamesRepository::class)]
class VideoGames
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_game = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_jeu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $game_url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdGame(): ?int
    {
        return $this->id_game;
    }

    public function setIdGame(int $id_game): static
    {
        $this->id_game = $id_game;

        return $this;
    }

    public function getNomJeu(): ?string
    {
        return $this->nom_jeu;
    }

    public function setNomJeu(?string $nom_jeu): static
    {
        $this->nom_jeu = $nom_jeu;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getGameUrl(): ?string
    {
        return $this->game_url;
    }

    public function setGameUrl(?string $game_url): static
    {
        $this->game_url = $game_url;

        return $this;
    }
}

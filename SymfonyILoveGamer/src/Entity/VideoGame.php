<?php

namespace App\Entity;

use App\Repository\VideoGameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGameRepository::class)]
class VideoGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?int $id_API = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $background_image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAPI(): ?int
    {
        return $this->id_API;
    }

    public function setIdAPI(int $id_API): static
    {
        $this->id_API = $id_API;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBackgroundImage(): ?string
    {
        return $this->background_image;
    }

    public function setBackgroundImage(?string $background_image): static
    {
        $this->background_image = $background_image;

        return $this;
    }
}

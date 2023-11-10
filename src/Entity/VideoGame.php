<?php

namespace App\Entity;

use App\Repository\VideoGameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGameRepository::class)]
class VideoGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $released = null;

    #[ORM\Column]
    private ?float $rating = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgUrl = null;

    #[ORM\ManyToMany(targetEntity: Platform::class, inversedBy: 'videoGames', cascade: ['PERSIST', 'REMOVE'])]
    private Collection $platfomrs;

    #[ORM\Column]
    private ?int $apiId = null;

    public function __construct()
    {
        $this->platfomrs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReleased(): ?\DateTimeInterface
    {
        return $this->released;
    }

    public function setReleased(\DateTimeInterface $released): static
    {
        $this->released = $released;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(?string $imgUrl): static
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * @return Collection<int, Platform>
     */
    public function getPlatfomrs(): Collection
    {
        return $this->platfomrs;
    }

    public function addPlatfomr(Platform $platfomr): static
    {
        if (!$this->platfomrs->contains($platfomr)) {
            $this->platfomrs->add($platfomr);
        }

        return $this;
    }

    public function removePlatfomr(Platform $platfomr): static
    {
        $this->platfomrs->removeElement($platfomr);

        return $this;
    }

    public function getApiId(): ?int
    {
        return $this->apiId;
    }

    public function setApiId(int $apiId): static
    {
        $this->apiId = $apiId;

        return $this;
    }
}

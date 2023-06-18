<?php

namespace App\Entity;

use App\Repository\TraductionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
#[ApiResource]

#[ORM\Entity(repositoryClass: TraductionRepository::class)]
class Traduction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $translatedWord = null;

    #[ORM\Column(length: 255)]
    private ?string $def = null;

    #[ORM\ManyToOne(inversedBy: 'traductions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Language $languageTrad = null;

    #[ORM\ManyToOne(inversedBy: 'traduction')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Word $wordLink = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranslatedWord(): ?string
    {
        return $this->translatedWord;
    }

    public function setTranslatedWord(?string $translatedWord): self
    {
        $this->translatedWord = $translatedWord;

        return $this;
    }

    public function getDef(): ?string
    {
        return $this->def;
    }

    public function setDef(string $def): self
    {
        $this->def = $def;

        return $this;
    }

    public function getLanguageTrad(): ?Language
    {
        return $this->languageTrad;
    }

    public function setLanguageTrad(?Language $languageTrad): self
    {
        $this->languageTrad = $languageTrad;

        return $this;
    }

    public function getWordLink(): ?Word
    {
        return $this->wordLink;
    }

    public function setWordLink(?Word $wordLink): self
    {
        $this->wordLink = $wordLink;

        return $this;
    }


}

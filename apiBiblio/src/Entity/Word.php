<?php

namespace App\Entity;

use App\Repository\WordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
#[ApiResource]

#[ORM\Entity(repositoryClass: WordRepository::class)]
class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $def = null;

    #[ORM\Column(length: 255)]
    private ?string $inputWord = null;

    #[ORM\Column(length: 255)]
    private ?string $wordType = null;

    #[ORM\ManyToOne(inversedBy: 'words')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Language $language = null;


    #[ORM\ManyToOne(inversedBy: 'words')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'words')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GroupWord $groupWord = null;

    #[ORM\ManyToOne(inversedBy: 'wordLink')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Traduction $traduction = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getInputWord(): ?string
    {
        return $this->inputWord;
    }

    public function setInputWord(string $inputWord): self
    {
        $this->inputWord = $inputWord;

        return $this;
    }

    public function getWordType(): ?string
    {
        return $this->wordType;
    }

    public function setWordType(string $wordType): self
    {
        $this->wordType = $wordType;

        return $this;
    }


    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGroupWord(): ?GroupWord
    {
        return $this->groupWord;
    }

    public function setGroupWord(?GroupWord $groupWord): self
    {
        $this->groupWord = $groupWord;

        return $this;
    }

    public function getTraduction(): ?Traduction
    {
        return $this->traduction;
    }

    public function setTraduction(?Traduction $traduction): self
    {
        $this->traduction = $traduction;

        return $this;
    }
}

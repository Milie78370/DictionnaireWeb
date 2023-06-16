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

    #[ORM\OneToMany(mappedBy: 'traduction', targetEntity: Word::class)]
    private Collection $wordLink;

    public function __construct()
    {
        $this->wordLink = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Word>
     */
    public function getWordLink(): Collection
    {
        return $this->wordLink;
    }

    public function addWordLink(Word $wordLink): self
    {
        if (!$this->wordLink->contains($wordLink)) {
            $this->wordLink->add($wordLink);
            $wordLink->setTraduction($this);
        }

        return $this;
    }

    public function removeWordLink(Word $wordLink): self
    {
        if ($this->wordLink->removeElement($wordLink)) {
            // set the owning side to null (unless already changed)
            if ($wordLink->getTraduction() === $this) {
                $wordLink->setTraduction(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'language', targetEntity: Word::class)]
    private Collection $words;

    #[ORM\OneToMany(mappedBy: 'languageTrad', targetEntity: Traduction::class)]
    private Collection $traductions;

    public function __construct()
    {
        $this->words = new ArrayCollection();
        $this->traductions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Word>
     */
    public function getWords(): Collection
    {
        return $this->words;
    }

    public function addWord(Word $word): self
    {
        if (!$this->words->contains($word)) {
            $this->words[] = $word;
            $word->setLanguage($this);
        }

        return $this;
    }

    public function removeWord(Word $word): self
    {
        if ($this->words->removeElement($word)) {
            // set the owning side to null (unless already changed)
            if ($word->getLanguage() === $this) {
                $word->setLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Traduction>
     */
    public function getTraductions(): Collection
    {
        return $this->traductions;
    }

    public function addTraduction(Traduction $traduction): self
    {
        if (!$this->traductions->contains($traduction)) {
            $this->traductions->add($traduction);
            $traduction->setLanguageTrad($this);
        }

        return $this;
    }

    public function removeTraduction(Traduction $traduction): self
    {
        if ($this->traductions->removeElement($traduction)) {
            // set the owning side to null (unless already changed)
            if ($traduction->getLanguageTrad() === $this) {
                $traduction->setLanguageTrad(null);
            }
        }

        return $this;
    }

}

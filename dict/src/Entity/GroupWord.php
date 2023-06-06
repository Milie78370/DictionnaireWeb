<?php

namespace App\Entity;

use App\Repository\GroupWordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupWordRepository::class)]
class GroupWord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'groupWord', targetEntity: Word::class)]
    private Collection $words;

    public function __construct()
    {
        $this->words = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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
            $this->words->add($word);
            $word->setGroupWord($this);
        }

        return $this;
    }

    public function removeWord(Word $word): self
    {
        if ($this->words->removeElement($word)) {
            // set the owning side to null (unless already changed)
            if ($word->getGroupWord() === $this) {
                $word->setGroupWord(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->label;
    }
}

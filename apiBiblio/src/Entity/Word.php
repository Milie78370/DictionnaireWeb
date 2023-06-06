<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\WordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WordRepository::class)]
#[ApiResource]
class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("post:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("post:read")]
    #[Assert\NotBlank(message:"La definition du mot est obligatoire")]
    private ?string $def = null;

    #[ORM\Column(length: 255)]
    #[Groups("post:read")]
    #[Assert\NotBlank(message:"le mot dans le dictionnaire est obligatoire")]
    private ?string $inputWord = null;

    #[ORM\Column(length: 255)]
    #[Groups("post:read")]
    #[Assert\NotBlank(message:"le groupe de mot est obligatoire")]
    private ?string $wordType = null;

    #[ORM\OneToMany(mappedBy: 'word', targetEntity: Language::class)]
    private Collection $language;

    #[ORM\ManyToOne(inversedBy: 'words')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'words')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GroupWord $groupWord = null;

    public function __construct()
    {
        $this->language = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Language>
     */
    public function getLanguage(): Collection
    {
        return $this->language;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->language->contains($language)) {
            $this->language->add($language);
            $language->setWord($this);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        if ($this->language->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getWord() === $this) {
                $language->setWord(null);
            }
        }

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
}

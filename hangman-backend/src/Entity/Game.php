<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 * @ORM\Table(name="games")
 */
class Game
{
    /**
     * The maximum number of incorrect attempts allowed for a game.
     */
    const MAX_INCORRECT_ATTEMPTS = 6;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Word::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $word;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $win;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=GameAttempt::class, mappedBy="game", cascade={"persist", "remove"})
     */
    private $attempts;

    public function __construct()
    {
        $this->attempts = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    // Setters and Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWord(): Word
    {
        return $this->word;
    }

    public function setWord(Word $word): self
    {
        $this->word = $word;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function addAttempt(GameAttempt $attempt): self
    {
        if (!$this->attempts->contains($attempt)) {
            $this->attempts[] = $attempt;
            $attempt->setGame($this);
        }
        return $this;
    }

    public function getAttempts(): array
    {
        return $this->attempts->toArray();
    }

    public function getWinStatus(): ?bool
    {
        return $this->win;
    }

    public function setWinStatus(?bool $status): self
    {
        $this->win = $status;
        return $this;
    }

    public function hasLetterBeenGuessed(string $letter): bool
    {
        return in_array($letter, $this->getGuessedLetters(), true);
    }

    public function getGuessedLetters(): array
    {
        return array_unique(array_map(
            fn(GameAttempt $attempt) => $attempt->getLetter(),
            $this->attempts->toArray()
        ));
    }
}

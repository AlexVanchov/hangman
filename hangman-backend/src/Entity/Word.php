<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WordRepository::class)
 * @ORM\Table(name="words")
 */
class Word
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $word;

    // Getters and setters...

     public function getId(): ?int
     {
         return $this->id;
     }
 
     public function getWord(): ?string
     {
         return $this->word;
     }
 
     public function setWord(string $word): self
     {
         $this->word = $word;
 
         return $this;
     }
}

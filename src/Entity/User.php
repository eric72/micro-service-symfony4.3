<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $creationdate;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $updatedate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCreationdate()
    {
        return $this->creationdate;
    }

    public function setCreationdate(\datetime $creationdate): self
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    public function getUpdatedate()
    {
        return $this->updatedate;
    }

    public function setUpdatedate(\datetime $updatedate): self
    {
        $this->updatedate = $updatedate;

        return $this;
    }
}

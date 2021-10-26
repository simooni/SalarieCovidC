<?php

namespace App\Entity;

use App\Repository\TdocumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TdocumentRepository::class)
 */
class Tdocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20000)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type_document;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tdocuments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_create;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $supprimer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getTypeDocument(): ?string
    {
        return $this->type_document;
    }

    public function setTypeDocument(?string $type_document): self
    {
        $this->type_document = $type_document;

        return $this;
    }

    public function getUserCreate(): ?User
    {
        return $this->user_create;
    }

    public function setUserCreate(?User $user_create): self
    {
        $this->user_create = $user_create;

        return $this;
    }

    public function getSupprimer(): ?string
    {
        return $this->supprimer;
    }

    public function setSupprimer(?string $supprimer): self
    {
        $this->supprimer = $supprimer;

        return $this;
    }
}

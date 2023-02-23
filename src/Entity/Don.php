<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\DonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonRepository::class)]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"il faut saisir id_ben")]
    #[Assert\Positive]
    private ?int $id_ben = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"il faut saisir titre")]
    private ?string $titre = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"il faut saisir Qte")]
    #[Assert\Positive]
    private ?int $qte = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    #[Assert\NotBlank(message:"il choisir saisir id_cat")]
    private ?Categorie $id_cat = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"il faut saisir type_cat")]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
   
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"il faut saisir choisir local")]
    #[Assert\Positive]
    private ?int $id_local = null;

    #[ORM\Column(length: 255)]
    
    
    private ?string $imge = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdBen(): ?int
    {
        return $this->id_ben;
    }

    public function setIdBen(int $id_ben): self
    {
        $this->id_ben = $id_ben;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(?int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getIdCat(): ?Categorie
    {
        return $this->id_cat;
    }

    public function setIdCat(?Categorie $id_cat): self
    {
        $this->id_cat = $id_cat;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdLocal(): ?int
    {
        return $this->id_local;
    }

    public function setIdLocal(?int $id_local): self
    {
        $this->id_local = $id_local;

        return $this;
    }

    public function getImge(): ?string
    {
        return $this->imge;
    }

    public function setImge(?string $imge): self
    {
        $this->imge = $imge;

        return $this;
    }
}

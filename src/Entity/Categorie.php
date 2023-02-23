<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"il faut saisir type_cat")]
    private ?string $type_cat = null;
   
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"il faut saisir des_cat")]
    private ?string $des_cat = null;
    
    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"il faut saisir nom")]
    private ?string $nom = null;
    
    #[ORM\OneToMany(mappedBy: 'id_cat', targetEntity: Don::class)]
    private Collection $dons;

    public function __construct()
    {
        $this->dons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeCat(): ?string
    {
        return $this->type_cat;
    }

    public function setTypeCat(?string $type_cat): self
    {
        $this->type_cat = $type_cat;

        return $this;
    }

    public function getDesCat(): ?string
    {
        return $this->des_cat;
    }

    public function setDesCat(?string $des_cat): self
    {
        $this->des_cat = $des_cat;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Don>
     */
    public function getDons(): Collection
    {
        return $this->dons;
    }

    public function addDon(Don $don): self
    {
        if (!$this->dons->contains($don)) {
            $this->dons->add($don);
            $don->setIdCat($this);
        }

        return $this;
    }

    public function removeDon(Don $don): self
    {
        if ($this->dons->removeElement($don)) {
            // set the owning side to null (unless already changed)
            if ($don->getIdCat() === $this) {
                $don->setIdCat(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return (string) $this->getId();
    }
    
}

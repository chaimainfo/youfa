<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TableauRepository")
 * @UniqueEntity(
 *     fields= {"nom"} ,
 *     message= "Ce tableau existe deja"
 *     )
 */
/*
  * @UniqueEntity(
 *     fields= {"nom"} ,
 *     message= "Ce tableau existe deja"
 *     )
 */
class Tableau
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
    private $nom;

  //  /**
  //   * @ORM\OneToMany(targetEntity="App\Entity\Composant", mappedBy="tableau",cascade={"persist", "remove" })
 //    */
 //   private $composants;

  //  /**
  //   * @ORM\OneToMany(targetEntity="App\Entity\ListeCompo", mappedBy="tableau")
  //   */
  //  private $listeCompos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeTab", inversedBy="tableaux")
     */
    private $typeTab;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Composant", mappedBy="tableau")
     */
    private $composants;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;



    public function __construct()
    {
        $this->composants = new ArrayCollection();
        $this->droitAccees = new ArrayCollection();
        $this->listeCompos = new ArrayCollection();
        $this->ligne = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

  //  /**
  //   * @return Collection|Composant[]
 //    */
 /*   public function getComposants(): Collection
    {
        return $this->composants;
    }

    public function addComposant(Composant $composant): self
    {
        if (!$this->composants->contains($composant)) {
            $this->composants[] = $composant;
            $composant->setTableau($this);
        }

        return $this;
    }

    public function removeComposant(Composant $composant): self
    {
        if ($this->composants->contains($composant)) {
            $this->composants->removeElement($composant);
            // set the owning side to null (unless already changed)
            if ($composant->getTableau() === $this) {
                $composant->setTableau(null);
            }
        }

        return $this;
    } */

//    /**
//     * @return Collection|ListeCompo[]
//     */
 /*   public function getListeCompos(): Collection
    {
        return $this->listeCompos;
    }

    public function addListeCompo(ListeCompo $listeCompo): self
    {
        if (!$this->listeCompos->contains($listeCompo)) {
            $this->listeCompos[] = $listeCompo;
            $listeCompo->setTableau($this);
        }

        return $this;
    }

    public function removeListeCompo(ListeCompo $listeCompo): self
    {
        if ($this->listeCompos->contains($listeCompo)) {
            $this->listeCompos->removeElement($listeCompo);
            // set the owning side to null (unless already changed)
            if ($listeCompo->getTableau() === $this) {
                $listeCompo->setTableau(null);
            }
        }

        return $this;
    }*/

    public function getTypeTab(): ?TypeTab
    {
        return $this->typeTab;
    }

    public function setTypeTab(?TypeTab $typeTab): self
    {
        $this->typeTab = $typeTab;

        return $this;
    }

    /**
     * @return Collection|Composant[]
     */
    public function getComposants(): Collection
    {
        return $this->composants;
    }

    public function addComposant(Composant $composant): self
    {
        if (!$this->composants->contains($composant)) {
            $this->composants[] = $composant;
            $composant->setTableau($this);
        }

        return $this;
    }

    public function removeComposant(Composant $composant): self
    {
        if ($this->composants->contains($composant)) {
            $this->composants->removeElement($composant);
            // set the owning side to null (unless already changed)
            if ($composant->getTableau() === $this) {
                $composant->setTableau(null);
            }
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

}

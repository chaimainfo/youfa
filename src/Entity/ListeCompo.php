<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ListeCompoRepository")
 */
class ListeCompo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Poste", inversedBy="listeCompos")
     */
    private $poste;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Composant", inversedBy="listeCompos",cascade={"persist","remove" })
     * @ORM\JoinTable(name="liste_compo_composant")
     */
    private $composants;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DroitAccee", inversedBy="listeCompos")
     */
    private $droitAccee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeTab", inversedBy="listeCompos")
     */
    private $typeTab;

    public function __construct()
    {
        $this->composants = new ArrayCollection();
        $this->droitAccees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): self
    {
        $this->poste = $poste;

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
        }

        return $this;
    }

    public function removeComposant(Composant $composant): self
    {
        if ($this->composants->contains($composant)) {
            $this->composants->removeElement($composant);
        }

        return $this;
    }

    public function getDroitAccee(): ?DroitAccee
    {
        return $this->droitAccee;
    }

    public function setDroitAccee(?DroitAccee $droitAccee): self
    {
        $this->droitAccee = $droitAccee;

        return $this;
    }

    public function getTypeTab(): ?TypeTab
    {
        return $this->typeTab;
    }

    public function setTypeTab(?TypeTab $typeTab): self
    {
        $this->typeTab = $typeTab;

        return $this;
    }

}

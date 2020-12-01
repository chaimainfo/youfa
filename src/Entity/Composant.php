<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComposantRepository")
 */
class Composant
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contenu;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $enabled;

    //  /**
    //  * @ORM\ManyToOne(targetEntity="App\Entity\Tableau", inversedBy="composants",cascade={"persist","remove"})
    //  */
   // private $tableau;
    /**
     * @ORM\Column(type="string",nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ListeCompo", mappedBy="composants",cascade={"persist","remove"})
     * @ORM\JoinTable(name="liste_compo_composant")
     */
    private $listeCompos;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Liste", cascade={"persist", "remove"})
     */
    private $liste;


  //  /**
  //   * @ORM\ManyToOne(targetEntity="App\Entity\TypeTab", inversedBy="composants")
 //    * @ORM\JoinColumn(nullable=true)
 //    */
 //   private $typeTab;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tableau", inversedBy="composants")
     */
    private $tableau;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TypeTab", inversedBy="composants")
     */
    private $typesTab;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="composants")
     */
    private $lastEditor;

    public function __construct()
    {
        $this->droitsAccee = new ArrayCollection();
        $this->listeCompos = new ArrayCollection();
        $this->lignes = new ArrayCollection();
        $this->typesTab = new ArrayCollection();
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }



  /*  public function getTableau(): ?Tableau
    {
        return $this->tableau;
    }

    public function setTableau(?Tableau $tableau): self
    {
        $this->tableau = $tableau;

        return $this;
    }*/

    /**
     * @return Collection|ListeCompo[]
     */
    public function getListeCompos(): Collection
    {
        return $this->listeCompos;
    }

    public function addListeCompo(ListeCompo $listeCompo): self
    {
        if (!$this->listeCompos->contains($listeCompo)) {
            $this->listeCompos[] = $listeCompo;
            $listeCompo->addComposant($this);
        }

        return $this;
    }

    public function removeListeCompo(ListeCompo $listeCompo): self
    {
        if ($this->listeCompos->contains($listeCompo)) {
            $this->listeCompos->removeElement($listeCompo);
            $listeCompo->removeComposant($this);
        }

        return $this;
    }

    public function getListe(): ?Liste
    {
        return $this->liste;
    }

    public function setListe(?Liste $liste): self
    {
        $this->liste = $liste;

        return $this;
    }

  /*  public function getTypeTab(): ?TypeTab
    {
        return $this->typeTab;
    }

    public function setTypeTab(?TypeTab $typeTab): self
    {
        $this->typeTab = $typeTab;

        return $this;
    }*/

    public function getTableau(): ?Tableau
    {
        return $this->tableau;
    }

    public function setTableau(?Tableau $tableau): self
    {
        $this->tableau = $tableau;

        return $this;
    }

    /**
     * @return Collection|TypeTab[]
     */
    public function getTypesTab(): Collection
    {
        return $this->typesTab;
    }

    public function addTypesTab(TypeTab $typesTab): self
    {
        if (!$this->typesTab->contains($typesTab)) {
            $this->typesTab[] = $typesTab;
        }

        return $this;
    }

    public function removeTypesTab(TypeTab $typesTab): self
    {
        if ($this->typesTab->contains($typesTab)) {
            $this->typesTab->removeElement($typesTab);
        }

        return $this;
    }


    public function getLastEditor(): ?User
    {
        return $this->lastEditor;
    }

    public function setLastEditor(?User $lastEditor): self
    {
        $this->lastEditor = $lastEditor;

        return $this;
    }




}

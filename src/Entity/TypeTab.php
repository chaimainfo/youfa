<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeTabRepository")
 * @UniqueEntity(
 *     fields= {"type"} ,
 *     message= "Ce type existe deja"
 *     )
 */
class TypeTab
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
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tableau", mappedBy="typeTab")
     */
    private $tableaux;
   // /**
   //  * @ORM\OneToMany(targetEntity="App\Entity\Composant", mappedBy="typeTab")
   //  * @ORM\JoinColumn(nullable=true)
   //  */
   // private $composants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ListeCompo", mappedBy="typeTab")
     */
    private $listeCompos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Composant", mappedBy="typesTab")
     */
    private $composants;

    public function __construct()
    {
        $this->tableaux = new ArrayCollection();
    //    $this->listeCompos = new ArrayCollection();
        $this->composants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Tableau[]
     */
    public function getTableaux(): Collection
    {
        return $this->tableaux;
    }

    public function addTableaux(Tableau $tableaux): self
    {
        if (!$this->tableaux->contains($tableaux)) {
            $this->tableaux[] = $tableaux;
            $tableaux->setTypeTab($this);
        }

        return $this;
    }

    public function removeTableaux(Tableau $tableaux): self
    {
        if ($this->tableaux->contains($tableaux)) {
            $this->tableaux->removeElement($tableaux);
            // set the owning side to null (unless already changed)
            if ($tableaux->getTypeTab() === $this) {
                $tableaux->setTypeTab(null);
            }
        }

        return $this;
    }



  //  /**
  //   * @return Collection|Composant[]
   //  */
   /* public function getComposants(): Collection
    {
        return $this->composants;
    }

    public function addComposant(Composant $composant): self
    {
        if (!$this->composants->contains($composant)) {
            $this->composants[] = $composant;
            $composant->setTypeTab($this);
        }

        return $this;
    }

    public function removeComposant(Composant $composant): self
    {
        if ($this->composants->contains($composant)) {
            $this->composants->removeElement($composant);
            // set the owning side to null (unless already changed)
            if ($composant->getTypeTab() === $this) {
                $composant->setTypeTab(null);
            }
        }

        return $this;
    }
*/
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
            $listeCompo->setTypeTab($this);
        }

        return $this;
    }

    public function removeListeCompo(ListeCompo $listeCompo): self
    {
        if ($this->listeCompos->contains($listeCompo)) {
            $this->listeCompos->removeElement($listeCompo);
            // set the owning side to null (unless already changed)
            if ($listeCompo->getTypeTab() === $this) {
                $listeCompo->setTypeTab(null);
            }
        }

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
            $composant->addTypesTab($this);
        }

        return $this;
    }

    public function removeComposant(Composant $composant): self
    {
        if ($this->composants->contains($composant)) {
            $this->composants->removeElement($composant);
            $composant->removeTypesTab($this);
        }

        return $this;
    }


}

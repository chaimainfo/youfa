<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DroitAcceeRepository")
 */
class DroitAccee
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
    private $titre;

   // /**
   //  * @ORM\ManyToMany(targetEntity="App\Entity\Poste", mappedBy="droitsAccee",cascade={"persist", "remove" })
   //  * @ORM\JoinTable(name="poste_droit_accee")
   //  */
  //  private $postes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ListeCompo", mappedBy="droitAccee",cascade={"persist", "remove" })
     */
    private $listeCompos;



    public function __construct()
    {
        //$this->postes = new ArrayCollection();
        $this->composants = new ArrayCollection();
        $this->listeCompo = new ArrayCollection();

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

 //   /**
//     * @return Collection|Poste[]
//     */
/*    public function getPostes(): Collection
    {
        return $this->postes;
    }

    public function addPoste(Poste $poste): self
    {
        if (!$this->postes->contains($poste)) {
            $this->postes[] = $poste;
            $poste->addDroitsAccee($this);
        }

        return $this;
    }

    public function removePoste(Poste $poste): self
    {
        if ($this->postes->contains($poste)) {
            $this->postes->removeElement($poste);
            $poste->removeDroitsAccee($this);
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
            $listeCompo->setDroitAccee($this);
        }

        return $this;
    }

    public function removeListeCompo(ListeCompo $listeCompo): self
    {
        if ($this->listeCompos->contains($listeCompo)) {
            $this->listeCompos->removeElement($listeCompo);
            // set the owning side to null (unless already changed)
            if ($listeCompo->getDroitAccee() === $this) {
                $listeCompo->setDroitAccee(null);
            }
        }

        return $this;
    }

}

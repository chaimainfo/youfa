<?php

namespace App\Entity;

use App\Repository\DroitAcceeRepository;
use App\Repository\PosteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PosteRepository")
 * @UniqueEntity(
 *     fields= {"nom"} ,
 *     message= "Poste existant"
 *     )
 */
class Poste
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
  //   * @ORM\ManyToMany(targetEntity="App\Entity\DroitAccee", inversedBy="postes")
  //   * @ORM\JoinTable(name="poste_droit_accee")
 //    */
 //   private $droitsAccee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="poste")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ListeCompo", mappedBy="poste",cascade={"persist", "remove" })
     */
    private $listeCompos;

    public function __construct()
    {
        //$this->droitsAccee = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->listeCompos = new ArrayCollection();
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

 //   /**
 //    * @return Collection|DroitAccee[]
 //    */
/*    public function getDroitsAccee(): Collection
    {
        return $this->droitsAccee;
    }

    public function addDroitsAccee(DroitAccee $droitsAccee): self
    {
        if (!$this->droitsAccee->contains($droitsAccee)) {
            $this->droitsAccee[] = $droitsAccee;
        }

        return $this;
    }

    public function removeDroitsAccee(DroitAccee $droitsAccee): self
    {
        if ($this->droitsAccee->contains($droitsAccee)) {
            $this->droitsAccee->removeElement($droitsAccee);
        }

        return $this;
    }
*/
    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUsers(User $users): self
    {
        if (!$this->users->contains($users)) {
            $this->users[] = $users;
            $users->setPoste($this);
        }

        return $this;
    }

    public function removeUsers(User $users): self
    {
        if ($this->users->contains($users)) {
            $this->users->removeElement($users);
            // set the owning side to null (unless already changed)
            if ($users->getPoste() === $this) {
                $users->setPoste(null);
            }
        }

        return $this;
    }

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
            $listeCompo->setPoste($this);
        }

        return $this;
    }

    public function removeListeCompo(ListeCompo $listeCompo): self
    {
        if ($this->listeCompos->contains($listeCompo)) {
            $this->listeCompos->removeElement($listeCompo);
            // set the owning side to null (unless already changed)
            if ($listeCompo->getPoste() === $this) {
                $listeCompo->setPoste(null);
            }
        }

        return $this;
    }



}

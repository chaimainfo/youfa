<?php

namespace App\Controller;

use App\Entity\Composant;
use App\Entity\Liste;
use App\Entity\Tableau;
use App\Entity\TypeTab;
use App\Form\TableauType;
use App\Repository\ComposantRepository;
use App\Repository\DroitAcceeRepository;
use App\Repository\ListeCompoRepository;
use App\Repository\TableauRepository;
use App\Repository\TypeTabRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligne")
 */
class TableauController extends AbstractController
{
    /**
     * @Route("/", name="tableau_index", methods={"GET","POST"})
     */
    public function index(TypeTabRepository $ttr , ComposantRepository $cr,ObjectManager $om): Response
    {
        $composants=$cr->findAll();
        foreach ($composants as $c)
        {
            $c->setEnabled(false);
            $om->persist($c);
            $om->flush();
        }

        return $this->render('tableau/index.html.twig', [
            'typeTab' => $ttr->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_new", methods={"GET","POST"})
     */
    public function new(Request $request, ComposantRepository $cr, ObjectManager $om): Response
    {

        $tableau = new Tableau();
        $form = $this->createForm(TableauType::class, $tableau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableau->setDateCreation(new \DateTime());
            //-------------------------------------------------------------------
            $composants = array();
            $composantss = $cr->findAll();
            foreach ($composantss as $comp) {
                if ($comp->getTypesTab() != null) {
                    $typesTab = $comp->getTypesTab();
                    for ($i = 0; $i < count($typesTab); $i++) {
                        if ($typesTab[$i] == $tableau->getTypeTab()) {
                            $composants[] = $comp;
                        }
                    }
                }

            }
            //-------------------------------------------------------------------
            //$composants = $cr->findBy(['typeTab' => $tableau->getTypeTab()]);
            for ($i = 0; $i < count($composants); $i++) {
                $c = new Composant();
                $c->setType($composants[$i]->getType());
                $c->setNom($composants[$i]->getNom());
                $c->setEnabled($composants[$i]->getEnabled());
                if ($composants[$i]->getType() == 'liste') {
                    $l = new Liste();
                    $l->setTitre($composants[$i]->getListe()->getTitre());
                    $l->setContenu($composants[$i]->getListe()->getContenu());
                    $c->setListe($l);
                }
                $c->setTableau($tableau);
                $om->persist($c);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tableau);
            $entityManager->flush();

            return $this->redirectToRoute('tableau_index');
        }

        return $this->render('tableau/new.html.twig', [
            'tableau' => $tableau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tableau_show", methods={"GET"})
     */
    public function show(Tableau $tableau): Response
    {
        $user = $this->getUser();
        dump($user);
        $poste = $user->getPoste();

        $composants = $tableau->getComposants();
        return $this->render('tableau/show.html.twig', [
            'tableau' => $tableau,
            'composants' => $composants,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tableau_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tableau $tableau): Response
    {
        $form = $this->createForm(TableauType::class, $tableau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tableau_index', [
                'id' => $tableau->getId(),
            ]);
        }

        return $this->render('tableau/edit.html.twig', [
            'tableau' => $tableau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/remove",name="remove_ligne")
     */
    public function removeLigne(Tableau $ligne, ComposantRepository $cr, ObjectManager $om, TypeTabRepository $ttr)
    {
        $typeTab = $ttr->findOneBy(['type' => $ligne->getTypeTab()->getType()]);
        dump($typeTab);
        $composants = $cr->findBy(['tableau' => $ligne]);
        for ($i = 0; $i < count($composants); $i++) {
            $om->remove($composants[$i]);
            $om->flush();
        }
        $typeTab->removeTableaux($ligne);
        $om->persist($typeTab);
        $om->remove($ligne);
        $om->flush();
        dump($composants);
        dump($ligne);
        return $this->redirectToRoute('showTab', ['type' => $typeTab->getType()]);
    }

    public function isIn(string $s, array $tab)
    {
        $i = 0;
        while ($i < count($tab) && $tab[$i] != $s) {
            $i++;
        }
        return ($i < count($tab) && $tab[$i] == $s);
    }

    /**
     * @Route("/{type}/afficher)",name="showTab")
     */
    public function afficher(TypeTab $typeTab, TableauRepository $tr, ComposantRepository $cr, DroitAcceeRepository $dar, ListeCompoRepository $lcr, ObjectManager $om)
    {
        $lignes = $tr->findBy(['typeTab' => $typeTab]);
        $composant = $cr->findAll();
        $composantsPrincipaux = array();
        foreach ($composant as $compo) {
            $types = $compo->getTypesTab();
            $typesTab = array();
            for ($i = 0; $i < count($types); $i++) {
                $typesTab[] = $types[$i]->getType();
            }
            if ($this->isIn($typeTab->getType(), $typesTab)) {
                $composantsPrincipaux[] = $compo;
            }
        }
        $composantsDesLignes = array();
        foreach ($composant as $c) {
            if ($c->getTableau()) {
                if ($c->getTableau()->getTypeTab() == $typeTab) {
                    $composantsDesLignes[] = $c;
                }
            }
        }
        //dump($composantsDesLignes);
        $droit = $dar->findOneBy(['titre' => 'modifier']);
        //dump($droit);
        //dump($this->getUser()->getPoste()->getNom());
        $listeCompoDroit = $lcr->findOneBy(['poste' => $this->getUser()->getPoste(), 'typeTab' => $typeTab, 'droitAccee' => $droit]);
        //dump($listeCompoDroit->getComposants()->getValues());
$modifier=false;
        if ($listeCompoDroit) {
            $compoEnabled = $listeCompoDroit->getComposants();
            $cmpEnabled = array();
            foreach ($compoEnabled as $ce) {
                for ($i = 0; $i < count($composantsDesLignes); $i++) {
                    $cl = $composantsDesLignes[$i];
                    if ($cl->getNom() == $ce->getNom()) {
                        $cl->setEnabled(true);
                        $om->persist($cl);
                        $om->flush();
                    }
                }
            }
            $modifier=true;
        }
        dump($composantsDesLignes);


        return $this->render('tableau/show.html.twig', [
            'lignes' => $lignes,
            'compLigne' => $composantsDesLignes,
            'composants' => $composantsPrincipaux,
            'typeTab' =>$typeTab,
            'modifier'=>$modifier,

        ]);
    }

    public function posEspace(string $s)
    {
        $tab = array();
        for ($i = 0; $i < strlen($s); $i++) {
            if ($s[$i] == ' ') {
                $tab[] = $i;
            }
        }
        return $tab;
    }

    public function remplacerEspace(string $phrase, string $c)
    {
        for ($i = 0; $i < strlen($phrase); $i++) {
            if ($phrase[$i] == ' ') {
                $phrase[$i] = $c;
            }
        }
        return $phrase;
    }

    /**
     * @Route("/{id}/saveTab",name="saveTab")
     */
    public function save(Tableau $tableau, Request $request, ComposantRepository $cr, ObjectManager $om)
    {
        $composants = $cr->findBy(['tableau' => $tableau]);
        //dump($request->request->get('mode_de_payement'));
        for ($i = 0; $i < count($composants); $i++) {
            //dump($composants[$i]->getNom());
            $nom = $composants[$i]->getNom();
            $ancienContenu = $composants[$i]->getContenu();
            if (count($this->posEspace($composants[$i]->getNom())) != 0) {
                $nom = $this->remplacerEspace($composants[$i]->getNom(), '_');
            }


            if ($ancienContenu != $request->request->get($nom)) {
                $composants[$i]->setLastEditor($this->getUser());
            }
            //dump($request->request->get($nom));
            $composants[$i]->setContenu($request->request->get($nom));
            $om->persist($composants[$i]);
            $om->flush();
          //  dump($this->getUser()->getLogin());

        }
       //dump($composants);
          return $this->redirectToRoute('showTab', ['type' => $tableau->getTypeTab()->getType()]);
    }

    /**
     * @Route("/{type}/filtre",name="filtrage_tab")
     */
    public function filter(TypeTab $typeTab, ComposantRepository $cr, Request $request)
    {
        $comps = $cr->findAll();
        $comp=array();
        foreach ($comps as $c){
            if($c->getTypesTab()[0]==$typeTab){
                $comp[]=$c;
            }
        }
        dump($comps);
        return $this->render('tableau/filter.html.twig', [
            'composants' => $comp,
            'typeTab' => $typeTab]);
    }

    public function remplacerParEspc(string $phrase)
    {
        for ($i = 0; $i < strlen($phrase); $i++) {
            if ($phrase[$i] == '_') {
                $phrase[$i] = ' ';
            }
        }
        return $phrase;
    }

  //  /**
  //   * @Route("{type}/faireFiltre",name="faireFiltre")
  //   */
  /*  public function faire_filtre(TypeTab $typeTab, Request $request)
    {
        $c = $request->request->keys();
        //dump($c);
        $comp = array();
        for ($i = 0; $i < count($c); $i++) {
            $comp[] = $this->remplacerParEspc($c[$i]);
        }
        dump($comp);
        return $this->render('tableau/faireFiltre.html.twig',
            [
                'comp' => $comp,
                'typeTab' => $typeTab,
            ]);

    }*/

    /**
     * @Route("{type}/filtrage",name="filtrage")
     */
    public function filtrage(TypeTab $typeTab, Request $request, ComposantRepository $cr, TableauRepository $tr, ObjectManager $om)
    {
        $k = $request->request->keys();
        dump($k);
        $keys = array();
        for ($j = 0; $j < count($k); $j++) {
            if ($request->request->get($k[$j]) != "") {
                $keys[] = $k[$j];
            }
        }
        dump($request->request->all());

        $tableaux = $tr->findBy(['typeTab' => $typeTab]);
        $nbr = 0;
        $lignes = array();
        foreach ($tableaux as $tbl) {
            if (count($keys) > 0) {
                $true = true;
                for ($i = 0; $i < count($keys); $i++) {
                    $composants = $cr->findBy(['nom' => $this->remplacerParEspc($keys[$i]), 'tableau' => $tbl]);
                    foreach ($composants as $cmp) {
                        if ($request->request->get($keys[$i]) != $cmp->getContenu()) {
                            $true = false;
                        }
                    }
                }

                if ($true) {
                    $nbr++;
                    $lignes[] = $tbl;
                }
            } else {
                $composants = $cr->findBy(['tableau' => $tbl]);

                $true = true;
                foreach ($composants as $cmp) {
                    if (null != $cmp->getContenu()) {
                        $true = false;
                    }

                }
                if ($true) {
                    $nbr++;
                    $lignes[] = $tbl;
                }
            }

        }
        $li = $lignes;
//----------------------------------------------------------------------------------------------------
        if ($this->isIn('dateDebut', $keys) || $this->isIn('dateFin', $keys)) {
            $li = array();
            for ($l = 0; $l < count($lignes); $l++) {
                $dd = $request->request->get('dateDebut')/*|date('yy/mm/dd')*/
                ;
                dump($dd);
                $df = $request->request->get('dateFin')/*|date('yy/mm/dd')*/
                ;
                dump($df);
                $date = ($lignes[$l]->getDateCreation())->format('YY-mm-dd');
                dump(($lignes[$l]->getDateCreation())->format('YY-mm-dd'));
                if ($date <= $df && $date > $dd) {
                    $li[] = $lignes[$l];
                } else {
                    $nbr--;
                }
            }
        }
//---------------------------------------------------------------------------------------------------------------------------
        $cm = $cr->findAll();
        $composantss = array();
        foreach ($cm as $c) {
            if ($c->getTypesTab()[0] == $typeTab) {
                $composantss[] = $c;
            }
        }

        return $this->render('tableau/resultatFiltre.html.twig', [
            'nombre' => $nbr,
            'typeTab' => $typeTab,
            'lignes' => $li,
            'composants' => $composantss,
            'compLigne' => $cr->findBy(['tableau' => $tr->findBy(['typeTab' => $typeTab])]),
        ]);
    }

        /*  public function filtrage(TypeTab $typeTab, Request $request, ComposantRepository $cr, TableauRepository $tr, ObjectManager $om)
          {

              $keys = $request->request->keys();
              $n = count($keys);
              dump($keys[0]);
              $tableau = $tr->findBy(['typeTab' => $typeTab]);
              dump($tableau);
              $nt = count($tableau);
              //dump($nt);//  2
              $nbr = 0;

              $tab = array();
              for ($i = 0; $i < count($keys); $i++) {
                  $comp = $cr->findBy(['tableau' => $tableau, 'nom' => $this->remplacerParEspc($keys[$i])]);
                //  $tab=$tab+$comp;
                  $tab = array_merge($tab, $comp);
              }

              dump($tab);
              $tt = array();
              for ($i = 0; $i < $nt; $i++) {
                  $p = $i;
                  for ($j = 0; $j < $n; $j++) {
                      $tt[] = $tab[$p];
                      $p = $p + $nt;
                  }
                  dump($tt);
                  dump(count($tt));
                  $true = true;
                  for ($k = 0; $k < count($tt); $k++) {
                      if ($tt[$k]->getContenu() != $request->request->get($keys[$k])) {
                          $true = false;
                      }
                  }
                  if ($true == true) {
                      $nbr++;
                  }

                  $tt = array();
              }
              dump($nbr);
              //dump($nbr);
              return $this->render('tableau/resultatFiltre.html.twig', [
                  'nombre' => $nbr,
                  'typeTab' => $typeTab,
              ]);
          }*/
}
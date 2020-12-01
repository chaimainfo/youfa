<?php

namespace App\Controller;

use App\Entity\Composant;
use App\Entity\DroitAccee;
use App\Entity\TypeTab;
use App\Repository\ListeCompoRepository;
use App\Repository\TableauRepository;
use App\Repository\TypeTabRepository;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ComposantRepository;
use App\Repository\DroitAcceeRepository;
use App\Repository\PosteRepository;
use App\Repository\UserRepository;
use App\Entity\Poste;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\ListeCompo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ListeCompoController extends AbstractController
{
    /**
     * @Route("/liste/compo", name="liste_compo")
     */
    public function index(ListeCompoRepository $lcr , ComposantRepository $cr,ObjectManager $om)
    {  $composants=$cr->findAll();
        foreach ($composants as $c)
        {
            $c->setEnabled(false);
            $om->persist($c);
            $om->flush();
        }

        // $listes=$lcr->findAll();
        $listes = $lcr->findBy(array(), array('poste' => 'asc'));
        return $this->render('liste_compo/index.html.twig', [
            'listes' => $listes,
            'i' => 0,
        ]);
    }

    /**
     * @Route("{id}/affectation",name="affectation")
     */
    public function affecterDroitAccee(Poste $poste, Request $request, ComposantRepository $crep, TypeTabRepository $ttr, DroitAcceeRepository $drep, ObjectManager $em)
    {
        //$listeCompo = new ListeCompo();
        $c = $crep->findAll();
        $composants = array();

        for ($i = 0; $i < count($c); $i++) {
            if ($c[$i]->getTypesTab() != null) {
                $composants[] = $c[$i];
            }
        }
        $droits = $drep->findAll();

        return $this->render('liste_compo/listeDAC.html.twig', [
            'composants' => $composants,
            'poste' => $poste,
            'droits' => $droits,
            'id' => $poste->getId(),
            'typeTab' => $ttr->findAll(),
            'tab' => 'rien',
        ]);
    }



    /**
     * @Route("/{type}/{idp}/affect",name="affectation1")
     * @ParamConverter("type", options={"mapping": {"type": "type"}})
     * @ParamConverter("poste", options={"mapping": {"idp": "id"}})
     */
    public function affecterDroitAccee1(TypeTab $type, Poste $poste, Request $request, TableauRepository $tr, ComposantRepository $crep, DroitAcceeRepository $drep, ObjectManager $em)
    {
        $listeCompo = new ListeCompo();
        $c = $crep->findAll();
        // $composants = array();
        $tableau = $tr->findOneBy(['typeTab' => $type]);
        $composants = $crep->findBy(['tableau' => $tableau]);
        /*for ($i = 0; $i < count($c); $i++) {
            if ($c[$i]->getTypeTab() != null) {
                $composants[] = $c[$i];
            }
        }*/
        $droits = $drep->findAll();
        dump($type->getType());
        return $this->render('liste_compo/listeDAC.html.twig',
            [
                'composants' => $composants,
                'poste' => $poste,
                'droits' => $droits,
                'id' => $poste->getId(),
                'typeTab' => $type,
                'tab' => $type->getType(),
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

    public function remplacerParEspc(string $phrase)
    {
        for ($i = 0; $i < strlen($phrase); $i++) {
            if ($phrase[$i] == '_') {
                $phrase[$i] = ' ';
            }
        }
        return $phrase;
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
     * @Route("/{id}/{type}/affecterDAC",name="affecterDAC")
     * @ParamConverter("poste", options={"mapping": {"id": "id"}})
     * @ParamConverter("type", options={"mapping": {"type": "type"}})
     */
    public function affecterDAC(Poste $poste, TypeTab $type, Request $request, ObjectManager $om, ComposantRepository $cr, ListeCompoRepository $lcr, DroitAcceeRepository $dar)
    {
        $c = $request->request->keys();
        $all = $request->request->all();
        for ($i = 0; $i < count($c); $i++) {
            $cmpName = $c[$i];
            $droits = $all[$cmpName];
            dump($cmpName);
            dump($type);

            for ($j = 0; $j < count($droits); $j++) {
                $droitAccee = $dar->findOneBy(['titre' => $droits[$j]]);
                $liste = $lcr->findOneBy(['poste' => $poste, 'typeTab' => $type, 'droitAccee' => $droitAccee]);
                if ($liste == null) {
                    $newListe = new ListeCompo();
                    $newListe->setPoste($poste);
                    $newListe->setTypeTab($type);
                    $newListe->setDroitAccee($droitAccee);
                    // $compo = $cr->findOneBy(['nom' => $this->remplacerParEspc($cmpName), 'typeTab' => $type]);
                    //***********************************************************************************
                    $composs = $cr->findBy(['nom' => $this->remplacerParEspc($cmpName)]);
                    foreach ($composs as $coo) {
                        $tab = array();
                        for ($p = 0; $p < count($coo->getTypesTab()); $p++) {
                            $n = $coo->getTypesTab()[$p];
                            $tab[] = $n->getType();
                        }
                        if ($coo->getTypesTab() and $this->isIn($type->getType(), $tab)) {
                            $compo = $coo;
                        }
                    }
                    //***********************************************************************************
                    if ($droits[$j] == 'modifier') {
                        $compo->setEnabled(true);

                    }
                    //dump($compo);
                    $newListe->addComposant($compo);
                    $om->persist($newListe);
                    $om->flush();
                } else {
                    $compo = $liste->getComposants();
                    $cmp = array();
                    foreach ($compo as $cm) {
                        $cmp[] = $cm->getNom();
                    }
                    if (!$this->isIn($cmpName, $cmp)) {
                        //$compo = $cr->findOneBy(['nom' => $this->remplacerParEspc($cmpName), 'typeTab' => $type]);
                        $composs = $cr->findBy(['nom' => $this->remplacerParEspc($cmpName)]);
                        foreach ($composs as $coo) {
                            $tab = array();
                            for ($p = 0; $p < count($coo->getTypesTab()); $p++) {
                                $n = $coo->getTypesTab()[$p];
                                $tab[] = $n->getType();
                            }
                            if ($coo->getTypesTab() and $this->isIn($type->getType(), $tab)) {
                                $compo = $coo;
                            }

                            if ($droits[$j] == 'modifier') {
                                $compo->setEnabled(true);

                            }
                        }
                        $liste->addComposant($compo);
                        $om->persist($liste);
                        $om->flush();
                    }
                }
            }
        }

        return $this->redirectToRoute('liste_compo');
    }
    /*public function affecterDAC(Poste $poste, TypeTab $type, Request $request, ObjectManager $om, ComposantRepository $cr, ListeCompoRepository $lcr, DroitAcceeRepository $dar)
    {
        $c = $request->request->keys();
        $all = $request->request->all();
        dump($c);
        dump($all);
        for ($i = 0; $i <count($c); $i++) {
            $cmpName = $c[$i];
            $droits = $all[$cmpName];
            dump($cmpName);
            dump($type);
            for ($j = 0; $j < count($droits); $j++) {
                $droitAccee = $dar->findOneBy(['titre' => $droits[$j]]);
                $liste = $lcr->findOneBy(['poste' => $poste, 'typeTab' => $type, 'droitAccee' => $droitAccee]);
                if ($liste == null) {
                    $newListe = new ListeCompo();
                    $newListe->setPoste($poste);
                    $newListe->setTypeTab($type);
                    $newListe->setDroitAccee($droitAccee);
     //************************************************************************************
    $compos = $cr->findBy(['nom' => $this->remplacerParEspc($cmpName)]);
                    $compo = array();
                    foreach ($compos as $c) {
                        $t = array();
                        $types = $c->getTypesTab();
                        if ($types) {
                            for ($m = 0; $m < count($types); $m++) {
                                $t[] = $types[$m]->getType();
                            }
                        }
                        if ($this->isIn($type->getType(), $t)) {
                            $compo = $c;
                        }
                    }
                    if ($droits[$j] == 'modifier') {
                        $compo->setEnabled(true);
                    }
    //*************************************************************************************
                    $newListe->addComposant($compo);
                    $om->persist($newListe);
                    $om->flush();
                } else {
                   $compo = $liste->getComposants();
                    $cmp = array();
                    foreach ($compo as $cm) {
                        $cmp[] = $cm->getNom();
                    }
                    if (!$this->isIn($cmpName, $cmp)) {
                        $compos = $cr->findBy(['nom' => $this->remplacerParEspc($cmpName)]);
                        $compo = array();
                        foreach ($compos as $c) {
                            $t = array();
                            $types = $c->getTypesTab();
                            if ($types) {
                                for ($m = 0; $m < count($types); $m++) {
                                    $t[] = $types[$m]->getType();
                                }
                            }
                            if ($this->isIn($type->getType(), $t)) {
                                $compo = $c;
                            }
                        }
                        if ($droits[$j] == 'modifier') {
                            $compo->setEnabled(true);

                        }
                        $liste->addComposant($compo);
                        $om->persist($liste);
                        $om->flush();
                    }
                }
            }
        }

        return $this->redirectToRoute('liste_compo');

    }*/


    /**
     * @Route("{idp}/{idd}/{idt}/listeComposants",name="showListe")
     * @ParamConverter("poste", options={"mapping": {"idp" : "id"}})
     * @ParamConverter("droitAccee", options={"mapping": {"idd"   : "id"}})
     * @ParamConverter("typeTab", options={"mapping": {"idt"   : "id"}})
     */

    public function listeCompo(Poste $poste, DroitAccee $droitAccee, TypeTab $typeTab, ListeCompoRepository $lcr)
    {
        // dump($poste);dump($droitAccee);
        // dump($typeTab);
        $liste = $lcr->findOneBy(['poste' => $poste,
            'droitAccee' => $droitAccee,
            'typeTab' => $typeTab]);
        dump($liste->getComposants()->getValues());
        $composants = $liste->getComposants()->getValues();

        // dump($composants);

        return $this->render('liste_compo/showListe.html.twig', [
            'composants' => $composants,
            'poste' => $poste,
            'titre_droit' => $droitAccee->getTitre(),
            'liste' => $liste,
        ]);
    }

    /**
     * @Route("{id}/{listeId}/composantRemove",name="supprimerComposant")
     * @ParamConverter("composant", options={"mapping": {"id" : "id"}})
     * @ParamConverter("liste", options={"mapping": {"listeId"   : "id"}})
     */
    public function supprimerComposant(Composant $composant, ListeCompo $liste, ObjectManager $em)
    {
        $liste->removeComposant($composant);

        $em->persist($liste);
        $em->flush();
        return $this->redirectToRoute('liste_compo');
        /*return $this->redirectToRoute('showListe',[
            'idp'=>$liste->getPoste()->getId(),
            'idd'=>$liste->getDroitAccee()->getId(),
         ]);*/
    }

    /**
     * @Route("{id}/liste/remove",name="removeListe")
     */
    public function removeListe(ListeCompo $liste, ObjectManager $om, ComposantRepository $cr, TypeTabRepository $ttr)
    {
        $composants = $liste->getComposants();
        dump($composants);
        foreach ($composants as $c) {
            $liste->removeComposant($c);

        }
        $om->remove($liste);
        $om->flush();

        return $this->redirectToRoute('liste_compo');
    }

    /**
     * @Route("/{id}/liste/edit",name="editListe")
     */
    public function editListe(ListeCompo $liste, Request $request, TypeTabRepository $ttr, ComposantRepository $cr, DroitAcceeRepository $dar)
    {
        $compos = $cr->findAll();
        $composants = array();
        foreach ($compos as $c) {
            if ($c->getTypesTab()) {
                if (($c->getTypesTab()[0]) == $liste->getTypeTab()) {
                    $composants[] = $c;
                }
            }
        }

        return $this->render('liste_compo/edit.html.twig', [
            'poste' => $liste->getPoste(),
            'liste' => $liste,
            // 'droit' => $liste->getDroitAccee(),
            // 'id' => $liste->getId(),
            'composants' => $composants,
            // 'typeTab' => $liste->getTypeTab(),
            'droit' => $liste->getDroitAccee()->getTitre(),
        ]);
    }

    /**
     * @Route("/{id}/effectuerEdit",name="effectuer_edit")
     */
    public function effectuerEdit(ListeCompo $liste, TableauRepository $tr, Request $request, ObjectManager $om, DroitAcceeRepository $dar, ComposantRepository $cr)
    {
        $cmps = $request->request->get('comp');
        $composants = $liste->getComposants();
        $poste = $liste->getPoste();

        dump($cmps);
        foreach ($composants as $c) {
            if ($liste->getDroitAccee()->getTitre() == 'modifier') {
                $c->setEnabled(0);
                $om->persist($c);
            }

            $liste->removeComposant($c);
            $om->persist($liste);
            $om->flush();
        }
        foreach ($cmps as $cm) {
            $cmp = $cr->findBy(['nom' => $cm]);
            foreach ($cmp as $c) {
                if ($c->getTypesTab()[0] == $liste->getTypeTab()) {
                    $liste->addComposant($c);
                    $om->persist($c);
                    $om->flush();
                }
            }
        }

        return $this->redirectToRoute('liste_compo');
    }

}

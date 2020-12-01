<?php

namespace App\Controller;

use App\Entity\Composant;
use App\Entity\Liste;
use App\Entity\TypeComposant;
use App\Form\Composant1Type;
use App\Repository\ComposantRepository;
use App\Repository\ListeCompoRepository;
use App\Repository\ListeRepository;
use App\Repository\PosteRepository;
use App\Repository\TableauRepository;
use App\Repository\TypeTabRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/composant")
 */
class ComposantController extends AbstractController
{
    /**
     * @Route("/", name="composant_index", methods={"GET"})
     */
    public function index(ComposantRepository $composantRepository , ComposantRepository $cr,ObjectManager $om): Response
    {  $composants=$cr->findAll();
        foreach ($composants as $c)
        {
            $c->setEnabled(false);
            $om->persist($c);
            $om->flush();
        }
        $composants = array();
        $c = $composantRepository->findAll();
        for ($i = 0; $i < count($c); $i++) {

            if ($c[$i]->getTableau() == null) {
                $composants[] = $c[$i];
            }
        }
        return $this->render('composant/index.html.twig', [
            'composants' => $composants,
        ]);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * @Route("/new", name="composant_new", methods={"GET","POST"})
     */
    public function new(Request $request, TableauRepository $tr, ObjectManager $om, TypeTabRepository $ttr, PosteRepository $pr, ListeCompoRepository $lcr): Response
    {
        $composant = new Composant();

        $form = $this->createForm(Composant1Type::class, $composant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $typesTab = $composant->getTypesTab()->getValues();
            dump(count($typesTab));
            //while($i<count($typesTab))
            for ($i = 0; $i < count($typesTab); $i++) {
                dump($i);
                $comp = new Composant();
                $comp->setNom($composant->getNom());
                $comp->addTypesTab($typesTab[$i]);
                $comp->setType($request->request->get('type'));
                $comp->setEnabled(false);

                if ($request->request->get('type') == 'liste') {
                    $liste = new Liste();
                    $liste->setTitre($composant->getNom());
                    $comp->setListe($liste);
                    $comp->setContenu(null);
                }
                $comp->setEnabled(false);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($comp);

                $listes = $lcr->findBy(['poste' => $pr->findOneBy(['nom' => 'chef']), 'typeTab' => $typesTab[$i]]);
                foreach ($listes as $li) {
                    $li->addComposant($comp);
                    $entityManager->persist($li);
                }

                $entityManager->flush();
                $tabs = $tr->findBy(['typeTab' => $typesTab[$i]]);
                for ($j = 0; $j < count($tabs); $j++) {
                    $c = new Composant();
                    $c->setNom($composant->getNom());
                    $c->setTableau($tabs[$j]);
                    $c->setEnabled(false);
                    $c->setType($comp->getType());
                    if ($composant->getType() == 'liste') {
                        $l = new Liste();
                        $l->setTitre($composant->getNom());
                        $l->setContenu($composant->getListe()->getContenu());
                        $om->persist($l);

                        $c->setListe($l);
                        $om->persist($c);
                        $om->flush();
                    }

                    $om->persist($c);
                    $om->flush();
                }
                /*  if ($request->request->get('type') == 'liste') {
                      return $this->redirectToRoute('liste_new', ['titre' => $composant->getNom()]);
                  }*/
            }
            if ($request->request->get('type') == 'liste') {
                return $this->redirectToRoute('liste_new', ['titre' => $composant->getNom()]);
            }

            //   return $this->redirectToRoute('composant_index');
            /*  dump($request->request->get('type'));
              dump($composant->getTypesTab());
              $composant->setType($request->request->get('type'));
              if ($request->request->get('type') == 'liste') {
                  $liste = new Liste();
                  $liste->setTitre($composant->getNom());
                  $composant->setListe($liste);
                  $composant->setContenu(null);
              }
              $composant->setEnabled(true);
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($composant);
              $entityManager->flush();
              dump($composant->getTypesTab()->getValues());
              $typesTab = $composant->getTypesTab()->getValues();
              foreach ($typesTab as $tt) {
                  $tabs = $tr->findBy(['typeTab' => $tt]);
                  for ($i = 0; $i < count($tabs); $i++) {
                      $c = new Composant();
                      $c->setNom($composant->getNom());
                      $c->setTableau($tabs[$i]);
                      $c->setEnabled($composant->getEnabled());
                      $c->setType($composant->getType());
                      if ($composant->getType() == 'liste') {
                          $l = new Liste();
                          $l->setTitre($composant->getNom());
                          // $l->setContenu($composant->getListe()->getContenu());
                          $om->persist($l);
                          $c->setListe($l);
                      }

                      $om->persist($c);
                      $om->flush();
                  }

              }
              if ($request->request->get('type') == 'liste') {
                  return $this->redirectToRoute('liste_new', ['titre' => $composant->getNom()]);
              }
              return $this->redirectToRoute('composant_index');
  */
            return $this->redirectToRoute('composant_index');
        }
        return $this->render('composant/new.html.twig', [
            'composant' => $composant,
            'form' => $form->createView(),
        ]);
    }

    //  /**
    //   * @Route("/{id}/rl",name="remp_liste")
    //   */
    /* public function remp_liste(Liste $liste, ListeRepository $lr, ObjectManager $om)
     {
         $listes = $lr->findBy(['titre' => $liste->getTitre()]);
         for ($i = 0; $i < count($listes); $i++) {
             if ($liste->getId() != $listes[$i]->getId()) {
                 $listes[$i]->setContenu($liste->getContenu());
                 $om->flush();
             }
         }

         return $this->redirectToRoute('composant_index');
     }*/

    /**
     * @Route("/{id}", name="composant_show", methods={"GET"})
     */
    public function show(Composant $composant): Response
    {
        return $this->render('composant/show.html.twig', [
            'composant' => $composant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="composant_edit", methods={"GET","POST"})
     */
    public function edit(Composant $composant, Request $request, ObjectManager $om, ComposantRepository $cr, TableauRepository $tr)
    {
        $form = $this->createForm(Composant1Type::class, $composant);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $composants = $cr->findBy(['nom' => $composant->getNom()]);
            foreach ($composants as $cmp) {
                if (!$cmp->getTypesTab()) {
                    if ($cmp->getTableau()->getTypeTab() != $composant->getTypesTab()[0]) {
                        $om->remove($cmp);
                        $om->flush();
                    }
                }
            }
            $lignes= $tr->findBy(['typeTab'=>$composant->getTypesTab()[0]]);
            for($i=0;$i<count($lignes);$i++)
            {
                $c=new Composant();
                $c->setTableau($lignes[$i]);
                $c->setNom($composant->getNom());
                $c->setContenu(null);
                $c->setEnabled(false);
                $c->setType($composant->getType());
                $c->setLastEditor(null);
                $om->persist($c);
                $om->flush();
            }
            $om->persist($composant);
            $om->flush();
            return $this->redirectToRoute('composant_index');
        }
        return $this->render('composant/edit.html.twig', [
            'composant' => $composant,
            'form' => $form->createView(),
            dump($request->request->get('oui/non')),
        ]);
    }
    /* public function edit(Request $request, Composant $composant, ListeRepository $lr, ObjectManager $om, ComposantRepository $cr): Response
     {
         $ancienNom = $composant->getNom();
         $ancienType = $composant->getType();
         $ancienTypeTab=$composant->getTypesTab();
         dump($ancienTypeTab->getValues());
         dump($ancienTypeTab->getValues());
         $liste = null;
         if ($ancienType == 'liste') {
             $liste = $lr->findOneBy(['titre' => $ancienNom]);
         }
         $form = $this->createForm(Composant1Type::class, $composant);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $nouveauType = $request->request->get('type');
             $nouveauNom = $composant->getNom();
             $nouveauTypeTab=$composant->getTypesTab();
             if(array_diff($nouveauTypeTab->getValues(),$ancienTypeTab->getValues())!=null){
                 $composant->addTypesTab($nouveauTypeTab);
             }
             if ($nouveauType != null) {
                 $composant->setType($request->request->get('type'));
                 if ($request->request->get('type') == 'liste' && $ancienType=='liste') {
                     $liste = new Liste();
                     $liste->setTitre($composant->getNom());
                     $composant->setListe($liste);
                     $composant->setContenu(null);

                 }

               else {
                     return $this->redirectToRoute('liste_new', ['titre' => $composant->getNom()]);
                 }
                 return $this->redirectToRoute('composant_index');
             } else {
                 $composant->setType($ancienType);
                 if ($nouveauNom != $ancienNom) {
                     if ($ancienType == 'liste') {
                         $liste = $lr->findOneBy(['titre' => $ancienNom]);
                         $liste->setTitre($nouveauNom);
                         $composant->setListe($liste);
                         $om->persist($liste);
                     }
                     $composant->setContenu(null);
                     // $entityManager = $this->getDoctrine()->getManager();

                     $lc = $cr->findBy(['nom' => $ancienNom]);
                     for ($i = 0; $i < count($lc); $i++) {
                         $lc[$i]->setNom($nouveauNom);
                     }

                 } else {
                     $entityManager = $this->getDoctrine()->getManager();
                     //$entityManager->persist($composant);
                     $entityManager->persist($liste);
                     $entityManager->flush();
                 }

             }
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($composant);
             //$entityManager->persist($liste);
             $entityManager->flush();
             return $this->redirectToRoute('composant_index');
         }

         return $this->render('composant/edit.html.twig', [
             'composant' => $composant,
             'form' => $form->createView(),
             dump($request->request->get('oui/non')),
         ]);
     }*/

    /**
     * @Route("/{id}/delete", name="composant_delete")
     */
    public function delete(Composant $composant, Request $request, ComposantRepository $cr, ListeRepository $lr, ObjectManager $om, ListeCompoRepository $lcr)
    {


        $typeTab = $composant->getTypesTab()[0];
        dump($typeTab);
        $listesCompo = $lcr->findBy(['typeTab' => $typeTab]);

        foreach ($listesCompo as $lc) {
            $composants = $lc->getComposants();
            for ($i = 0; $i < count($composants); $i++) {
                if ($composants[$i]->getId() == $composant->getId()) {
                    $lc->removeComposant($composants[$i]);
                    $om->persist($lc);
                    //   $om->flush();
                }
            }
        }

        $compo = $cr->findBy(['nom' => $composant->getNom()]);
        foreach ($compo as $c) {
            if ($c->getTableau()) {
                if ($c->getTableau()->getTypeTab() == $typeTab) {
                    $om->remove($c);
                    // $om->flush();
                }
            }
        }

        $om->remove($composant);
        $om->flush();
        return $this->redirectToRoute('composant_index');
    }
    /* public function delete(Composant $composant, Request $request, ComposantRepository $cr, ListeRepository $lr, ObjectManager $om, ListeCompoRepository $lcr)
     {
         $nom = $composant->getNom();
         $lesOccurences = $cr->findBy(['nom' => $nom]);

         $typesTab = $composant->getTypesTab();
         $listeDc = array();
         for ($k = 0; $k < count($typesTab); $k++) {
             $listeDC[] = $lcr->findBy(['typeTab' => $typesTab[$k]]);
         }

         // $listeDC = $lcr->findBy(['typeTab' => $composant->getTypeTab()]);

         //dump(!empty($listeDC));
         if (!empty($listeDC)) {
             for ($i = 0; $i < count($listeDC); $i++) {
                 $listeDC[$i]->removeComposant($composant);

                 $om->persist($listeDC[$i]);
                 $om->flush();
             }
         }
         if ($composant->getType() != 'liste') {
             for ($i = 0; $i < count($lesOccurences); $i++) {
                 $om->remove($lesOccurences[$i]);
                 $om->flush();
             }
         } else {
             $lesOccDesListes = $lr->findBy(['titre' => $composant->getNom()]);
             //dump($lesOccDesListes);
             for ($i = 0; $i < count($lesOccurences); $i++) {
                 $om->remove($lesOccurences[$i]);
                 $om->flush();
             }
             for ($j = 0; $j < count($lesOccDesListes); $j++) {
                 $om->remove($lesOccDesListes[$j]);
                 $om->flush();
             }
         }
         return $this->redirectToRoute('composant_index');
     }*/
}

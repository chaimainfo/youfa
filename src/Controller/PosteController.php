<?php

namespace App\Controller;

use App\Entity\ListeCompo;
use App\Entity\Poste;
use App\Entity\User;
use App\Form\Poste1Type;
use App\Repository\ComposantRepository;
use App\Repository\DroitAcceeRepository;
use App\Repository\ListeCompoRepository;
use App\Repository\PosteRepository;
use App\Repository\TypeTabRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/poste")
 */
class PosteController extends AbstractController
{
    /**
     * @Route("/", name="poste_index", methods={"GET"})
     */
    public function index(PosteRepository $posteRepository , ComposantRepository $cr,ObjectManager $om): Response
    { $composants=$cr->findAll();
        foreach ($composants as $c)
        {
            $c->setEnabled(false);
            $om->persist($c);
            $om->flush();
        }

        return $this->render('poste/index.html.twig', [
            'postes' => $posteRepository->findAll(),
        ]);
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
     * @Route("/new", name="poste_new", methods={"GET","POST"})
     */
    public function new(Request $request, DroitAcceeRepository $dar, TypeTabRepository $ttr, ComposantRepository $cr, ListeCompoRepository $lcr): Response
    {

        $poste = new Poste();
        $form = $this->createForm(Poste1Type::class, $poste);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($poste);
            $entityManager->flush();

            if ($poste->getNom() == 'chef') {
                $droits = $dar->findAll();
                $typeTab = $ttr->findAll();

                foreach ($typeTab as $t) {
                    foreach ($droits as $d) {
                        $listeCompo = new ListeCompo();
                        $listeCompo->setDroitAccee($d);
                        $listeCompo->setTypeTab($t);
                        $listeCompo->setPoste($poste);
                        $entityManager->persist($listeCompo);
                        $entityManager->flush();
                    }
                }
                // $compos = $cr->findAll();
                $lc = $lcr->findBy(['poste' => $poste]);

                foreach ($lc as $l) {
                    $typeTL = $l->getTypeTab();
                    $compos = $cr->findAll();
                    foreach ($compos as $cm) {
                        $types = array();
                        $typesTC = $cm->getTypesTab();
                        if ($cm->getTypesTab()) {
                            for ($i = 0; $i < count($typesTC); $i++) {
                                $types[] = $typesTC[$i]->getType();
                            }
                            if ($this->isIn($typeTL->getType(), $types)) {
                                $l->addComposant($cm);
                                $entityManager->persist($cm);
                                $entityManager->persist($l);


                                $entityManager->flush();
                            }
                        }
                    }

                }
            }
            return $this->redirectToRoute('poste_index');

        }

        return $this->render('poste/new.html.twig', ['poste' => $poste,
            'form' => $form->createView(),]);
    }

    /**
     * @Route("/{id}/show", name="poste_show", methods={"GET"})
     */
    public
    function show(Poste $poste, ListeCompoRepository $lcr): Response
    {
        $list = $lcr->findBy(['poste' => $poste]);
        dump($list);
        $droits = array();
        for ($i = 0; $i < count($list); $i++) {
            $droits[] = $list[$i]->getDroitAccee();
        }
        //  $droits=$poste->getDroitsAccee();
        dump($droits);
        return $this->render('poste/show.html.twig', [
            'poste' => $poste,
            'id' => $poste->getId(),
            'droits' => $droits,
            'listComp' => $list,
            'aDroit' => count($list) > 0,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="poste_edit", methods={"GET","POST"})
     */
    public
    function edit(Request $request, Poste $poste, DroitAcceeRepository $dar): Response
    {

        $droitsDuPoste = $poste->getDroitsAccee();
        dump(count($droitsDuPoste->getValues()));
        $ttLesDroits = $dar->findAll();
        //dump($ttLesDroits);


        foreach ($ttLesDroits as $td) {
            $i = 0;
            while (($droitsDuPoste->getValues())[$i] != $td && $i < count($droitsDuPoste->getValues())) {
                $i++;
            }

            $droitNonCocher[] = $td;

        }

        dump($td);

        return $this->render('poste/edit.html.twig', [
            'poste' => $poste,
            'droit' => $droitsDuPoste,
            'id' => $poste->getId(),
        ]);
    }

    // /**
    //  * @Route("/{id}", name="poste_delete", methods={"DELETE"})
    //  */
    /*  public function delete(Request $request, Poste $poste): Response
      {
          if ($this->isCsrfTokenValid('delete' . $poste->getId(), $request->request->get('_token'))) {
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->remove($poste);
              $entityManager->flush();
          }

          return $this->redirectToRoute('poste_index');
      }
  */

    /**
     * @Route("/{id}/delete", name="poste_remove")
     */
    public function remove(Poste $poste, UserRepository $ur, ObjectManager $om, ListeCompoRepository $lcr)
    {
        $users = $ur->findBy(['poste' => $poste]);
        foreach ($users as $u) {
            $u->setIsActive(false);
            $u->setPoste(null);
            $om->persist($u);
            $om->flush();
        }

        $listesCompo = $lcr->findBy(['poste' => $poste]);
        foreach ($listesCompo as $liste) {
            $om->remove($liste);
            $om->flush();
        }
        $om->remove($poste);
        $om->flush();
        return $this->redirectToRoute('poste_index');
    }

}

<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Form\ListeType;
use App\Repository\ComposantRepository;
use App\Repository\ListeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/liste")
 */
class ListeController extends AbstractController
{
    /**
     * @Route("/", name="liste_index", methods={"GET"})
     */
    public function index(ListeRepository $listeRepository , ComposantRepository $cr,ObjectManager $om): Response
    {  $composants=$cr->findAll();
        foreach ($composants as $c)
        {
            $c->setEnabled(false);
            $om->persist($c);
            $om->flush();
        }

        $lst = $listeRepository->findAll();
        for ($i = 0; $i < count($lst); $i++) {
            if ($lst[$i] != null) {
                dump($lst[$i]);
                $j = $i + 1;
                for ($j = $i + 1; $j < count($lst); $j++) {
                    if ($lst[$i]->getTitre() == $lst[$j]->getTitre()) {
                        $lst[$j] = null;
                    }
                }
            }
        }
        $listes = array();
        for ($i = 0; $i < count($lst); $i++) {
            if ($lst[$i] != null) {
                $listes[] = $lst[$i];
            }
        }
        return $this->render('liste/index.html.twig', [
            'listes' => $listes,
        ]);
    }

    /**
     * @Route("/{titre}/new", name="liste_new", methods={"GET","POST"})
     */
    public function new(Liste $liste, Request $request, ObjectManager $om,ComposantRepository $cr): Response
    {
        if (!empty($request->request->all())) {
            $cnt = $request->request->get('content');
            $j = strlen($cnt);
            $i = 0;
            $tab = array();

            while ($i <= $j) {
                $c = '';
                while ($i < strlen($cnt) && $cnt[$i] != ';') {
                    $c = $c . $cnt[$i];
                    $i++;
                }
                $i++;
                $tab[] = $c;
            }
            $liste->setContenu($tab);
            $om->persist($liste);
            $om->flush();
            $comps=$cr->findBy(['nom'=>$liste->getTitre()]);
            foreach ($comps as $cmp)
            {
               $l=new Liste();
               $l->setTitre($liste->getTitre());
               $l->setContenu($liste->getContenu());
             if($cmp->getListe()==null){
                 $cmp->setListe($l);
                 $om->persist($cmp);
                 $om->flush();
             }
            }

            return $this->redirectToRoute('remp_liste', ['id' => $liste->getId()]);
        }
        dump($liste->getContenu());
        return $this->render('liste/new.html.twig', [
            'liste' => $liste,

        ]);
    }
    /**
     * @Route("/{id}/rl",name="remp_liste")
     */
    public function remp_liste(Liste $liste, ListeRepository $lr, ObjectManager $om)
    {
        $listes = $lr->findBy(['titre' => $liste->getTitre()]);
        for ($i = 0; $i < count($listes); $i++) {
            if ($liste->getId() != $listes[$i]->getId()) {
                $listes[$i]->setContenu($liste->getContenu());
                $om->flush();
            }
        }

        return $this->redirectToRoute('composant_index');
    }
    /**
     * @Route("/{id}", name="liste_show", methods={"GET"})
     */
    public function show(Liste $liste): Response
    {
        return $this->render('liste/show.html.twig', [
            'liste' => $liste,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="liste_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Liste $liste): Response
    {
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('liste_index', [
                'id' => $liste->getId(),
            ]);
        }

        return $this->render('liste/edit.html.twig', [
            'liste' => $liste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="liste_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Liste $liste): Response
    {
        if ($this->isCsrfTokenValid('delete' . $liste->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($liste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('liste_index');
    }
}

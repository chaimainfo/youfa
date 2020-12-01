<?php

namespace App\Controller;

use App\Entity\Composant;
use App\Entity\DroitAccee;
use App\Form\DroitAccee1Type;
use App\Repository\ComposantRepository;
use App\Repository\DroitAcceeRepository;
use App\Repository\ListeCompoRepository;
use App\Repository\TableauRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/droit/accee")
 */
class DroitAcceeController extends AbstractController
{
    /**
     * @Route("/", name="droit_accee_index", methods={"GET"})
     */
    public function index(DroitAcceeRepository $droitAcceeRepository , ComposantRepository $cr,ObjectManager $om): Response
    {  $composants=$cr->findAll();
        foreach ($composants as $c)
        {
            $c->setEnabled(false);
            $om->persist($c);
            $om->flush();
        }

        // dump($droitAcceeRepository->findAll());
        return $this->render('droit_accee/index.html.twig',[
            'droitAccees' => $droitAcceeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="droit_accee_new", methods={"GET","POST"})
     */
    public function new(Request $request,ObjectManager $om, DroitAcceeRepository $dar): Response
    {
        $droitAccee = new DroitAccee();
        $form = $this->createForm(DroitAccee1Type::class, $droitAccee);
        $form->handleRequest($request);
        $request->request->all();

        //dump($form->getData());

    //*****************************************************************************

    //*******************************************************************************
        if ($form->isSubmitted() && $form->isValid())
        {
           // dump($droitAccee->getComposants());
            dump($form->getData());

            $om->persist($droitAccee);
            $om->flush();

          return $this->redirectToRoute('droit_accee_index',[
              'droitAccees'=>$dar->findAll(),
          ]);
        }

        return $this->render('droit_accee/new.html.twig', [
            'droit_accee' => $droitAccee,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="droit_accee_show", methods={"GET"})
     */
    public function show(DroitAccee $droitAccee, ListeCompoRepository $lcr): Response
    {
        $listeCompo=$lcr->findBy(['droitAccee'=>$droitAccee]);
        dump($listeCompo);
       // $champs=$droitAccee->getComposants()->getValues();
        //dump($champs);

        return $this->render('droit_accee/show.html.twig', [
            'droit_accee' => $droitAccee,
            'liste'=>$listeCompo,

        ]);
    }

    /**
     * @Route("/{id}", name="droit_accee_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DroitAccee $droitAccee): Response
    {
        if ($this->isCsrfTokenValid('delete'.$droitAccee->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($droitAccee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('droit_accee_index');
    }

    /**
     * @Route("{id}/droitAcce/remove",name="droit_accee_remove")
     */
    public function removeDroit(DroitAccee $droitAccee, ListeCompoRepository $lcr, ObjectManager $om, TableauRepository $tr)
    {
        $listes=$lcr->findBy(['droitAccee'=>$droitAccee]);
        for($i=0;$i<count($listes);$i++)
        {
            $droitAccee->removeListeCompo($listes[$i]);
            $om->remove($listes[$i]);
            $om->flush();
           // dump($listes[$i]);
        }
        $om->remove($droitAccee);
        $om->flush();
        return $this->redirectToRoute('droit_accee_index');
    }
}

<?php

namespace App\Controller;

use App\Repository\ComposantRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TypeTabController extends AbstractController
{
    /**
     * @Route("/type/tab", name="type_tab")
     */
    public function index( ComposantRepository $cr,ObjectManager $om)
    {  $composants=$cr->findAll();
        foreach ($composants as $c)
        {
            $c->setEnabled(false);
            $om->persist($c);
            $om->flush();
        }

        return $this->render('type_tab/index.html.twig', [
            'controller_name' => 'TypeTabController',
        ]);
    }

}

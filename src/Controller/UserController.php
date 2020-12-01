<?php

namespace App\Controller;

use App\Entity\Composant;
use App\Entity\DroitAccee;
use App\Entity\ListeCompo;
use App\Entity\Poste;
use App\Entity\User;
use App\Form\Poste1Type;
use App\Form\User1Type;
use App\Repository\ComposantRepository;
use App\Repository\DroitAcceeRepository;
use App\Repository\PosteRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use function Symfony\Bridge\Twig\Extension\twig_get_form_parent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('security/inscription.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, PosteRepository $prep, UserPasswordEncoderInterface $encoder, ObjectManager $om): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);
        $postes = $prep->findAll();
        $password = $encoder->encodePassword($user, $user->getPassword());

        if ($form->isSubmitted() && $form->isValid()) {
            dump($user->getImage());
            $user->setPassword($password);
            $user->setImage($request->request->get('avatar'));
            $p = $prep->findOneBy(['id' => $request->request->get('poste')]);
            $user->setPoste($p);
            $om->persist($user);
            $om->flush();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'postes' => $postes
        ]);
        /*$form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);*/
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}/affectationDC",name="affectationDC")
     */
    public function affectationDC(DroitAccee $droitAccee, Request $request, ObjectManager $em, DroitAcceeRepository $dar, ComposantRepository $compRepo)
    {
        $compos = $request->request->get('c');

        dump($compos);
        for ($i = 0; $i < count($compos); $i++) {
            $cmp = $compRepo->findOneBy(array('nom' => $compos[$i]));
            $droitAccee->addComposant($cmp);
            $em->persist($droitAccee);
            $em->flush();

        }
        return $this->redirectToRoute('affecter_composant_droit', [
            'id' => $droitAccee->getId(),
        ]);
    }

    /**
     * @Route("/filtrage",name="user_filtre")
     */
    public function filtrage(PosteRepository $pr, Request $request)
    {

        $postes = $pr->findAll();
        dump($postes);
        return $this->render('user/choixFiltre.html.twig', [
            'postes' => $postes,
        ]);
    }

    public function posNull(array $tab, string $s)
    {
        $i = 0;
        while ($i < count($tab) && $tab[$i] != $s) {
            $i++;
        }
        if ($i < count($tab)) {
            return $i;
        } else {
            return -1;
        }
    }

    /**
     * @Route("/faireFiltre",name="faire_filtre")
     */
    public function faireFiltre(Request $request, UserRepository $urep)
    {

        $all = $request->request->all();

        $keys = $request->request->keys();
        $x = array();
        $filtreElements = array();
        $filtreKeys = array();
        for ($i = 0; $i < count($all); $i++) {
            if ($all[$keys[$i]] != "") {
                $filtreKeys[] = $keys[$i];
                $filtreElements[] = $all[$keys[$i]];
            }
        }
        dump($filtreKeys);
        dump($filtreElements);
        $usr0 = $urep->findBy([$filtreKeys[0] => $filtreElements[0]]);
        if(count($filtreKeys)==1)
        {


            foreach ($usr0 as $u)
            {$x[]=$u;}
        }
       else{
           for ($k = 1; $k < count($filtreKeys); $k++) {
            $x = array();
            $usr1 = $urep->findBy([$filtreKeys[$k] => $filtreElements[$k]]);
            for ($i = 0; $i < count($usr0); $i++) {
                for ($j = 0; $j < count($usr1); $j++) {
                    if ($usr0[$i]->getId() == $usr1[$j]->getId()) {
                        $x[] = $usr0[$i];
                    }
                }
            }
            $usr0 = $x;
        }
     }
        //dump(count($x));
        return $this->render('user/resultatFiltre.html.twig', [
            'nombre' => count($x),
            'users' => $x,
            'i' => 1,
        ]);
        //  dump($request->request->all());

        /*$all = $request->request->all();

        $keys = $request->request->keys();

        $filtreElements = array();
        $filtreKeys = array();
        for ($i = 0; $i < count($all); $i++) {
            if ($all[$keys[$i]] != "") {
                $filtreKeys[] = $keys[$i];
                $filtreElements[] = $all[$keys[$i]];
            }
        }
        $usr0 = $urep->findBy([$filtreKeys[0] => $filtreElements[0]]);
        dump($usr0);
        for ($k = 1; $k < count($filtreKeys); $k++) {
            $x = array();
            $usr1 = $urep->findBy([$filtreKeys[$k] => $filtreElements[$k]]);
            for ($i = 0; $i < count($usr0); $i++) {
                for ($j = 0; $j < count($usr1); $j++) {
                    if ($usr0[$i]->getId() == $usr1[$j]->getId()) {
                        $x[] = $usr0[$i];
                    }
                }
            }
            $usr0 = $x;
        }
        //dump(count($x));
        return $this->render('user/resultatFiltre.html.twig',[
            'nombre'=>count($x),
            'users'=> $x,
            'i'=>1,
        ]);*/
    }

}

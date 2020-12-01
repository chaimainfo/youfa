<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Entity\User;
use App\Form\User1Type;
use App\Form\UserType;
use App\Repository\ComposantRepository;
use App\Repository\PosteRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="security")
     */
    public function index(ComposantRepository $cr,ObjectManager $om)
    {  $composants=$cr->findAll();
        foreach ($composants as $c)
        {
            $c->setEnabled(false);
            $om->persist($c);
            $om->flush();
        }

        return $this->render('security/connexion.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/inscription",name="inscription")
     * @Method({"POST","GET"})
     */
// @IsGranted("ROLE_ADMIN")

    public function inscription(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager, PosteRepository $prep)
    {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);
        $password = $encoder->encodePassword($user, $user->getPassword());
        //$poste=$prep->findOneBy(array('nom'=>'gerant'));
        $postes = $prep->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $poste = $prep->findOneBy(['id' => $request->request->get('poste')]);
            $user->setPoste($poste);
            if ($user->getPoste()->getNom() == 'chef') {
                $user->setRole('role_admin');
            } else {
                $user->setRole('role_user');
            }
            $user->setIsActive(true);
            //$user->setRole($request->request->get('ROLE'));
            //dump($user->getPoste());
            //$user->setImage($request->request->get('avatar'));
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('user_index');
        }

        $request->getSession()->getFlashBag()->add('notice', 'Vous etes bien inscrit.');
        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView(),
            'postes' => $postes,

        ]);
    }

    /**
     * @Route("/connexion",name="connexion")
     */
    public function connexion(Request $request)
    {   if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
        return $this->redirectToRoute('tableau_index');
    }
        return $this->render("security/connexion.html.twig");

    }

    /**
     * @Route("/deconnexion",name="deconnexion")
     */
    public function deconnexion()
    {
        return $this->render("security/connexion.html.twig");
    }

}

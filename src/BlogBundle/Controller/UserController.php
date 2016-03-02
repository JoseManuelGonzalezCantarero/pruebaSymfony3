<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\User;
use BlogBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUserName = $authenticationUtils->getLastUsername();

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $user = new User();
                $user->setName($form->get("name")->getData());
                $user->setSurname($form->get("surname")->getData());
                $user->setEmail($form->get("email")->getData());

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($form->get("password")->getData(), $user->getSalt());
                $user->setPassword($password);
                $user->setRole('ROLE_USER');
                $user->setImage(null);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $flush = $em->flush();
                if($flush == null)
                {
                    $status = "El usuario se ha registrado correctamente";
                }
                else
                {
                    $status = "No te has registrado correctamente";
                }
            }
            else
            {
                $status = "No te has registrado correctamente";
            }

            $this->session->getFlashBag()->add("status", $status);
        }

        return $this->render('BlogBundle:user:login.html.twig', array(
            'error' => $error,
            'lastUserName' => $lastUserName,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/login_check", name="loginCheck")
     */
    public function loginCheckAction()
    {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }
}

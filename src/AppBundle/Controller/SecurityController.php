<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\User;
use AppBundle\Form\Task\UserTask;

/**
 * Class SecurityController
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @Route("/user/login", name="user_login")
     */
    public function userLoginAction(Request $request)
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('user/security/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/user/logout", name="user_logout")
     */
    public function userLogoutAction()
    {
        throw new \Exception('Please wait...');
    }

    /**
     * @Route("/user/registration", name="user_registration")
     */
    public function userRegistrationAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserTask::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_login');
        }

        return $this->render('user/security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/remind", name="user_remind")
     */
    public function userRemindAction()
    {
        throw new \Exception('<a href="/">homepage</a>');
    }
}
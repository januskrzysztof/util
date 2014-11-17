<?php

namespace Tutto\Bundle\UtilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Annotation
 */
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Tutto\Bundle\UtilBundle\Entity\Role;
use Tutto\Bundle\UtilBundle\Form\Type\LoginType;

/**
 * Class AuthorizationController
 * @package Tutto\Bundle\UtilBundle\Controller
 */
class AuthorizationController extends Controller {
    /**
     * @Route("/login", name="tutto_login")
     * @Method({"GET"})
     * @Template()
     */
    public function loginAction(Request $request) {
//        $userProvider = $this->get('tutto.user_provider');
//
//        $user = $userProvider->createUser();
//        $user->setPlainPassword('asdasdasd');
//        $user->setPlainUsername('fluke.kuczwa@gmail.com');
//        $user->setRole($this->getDoctrine()->getRepository(Role::class)->find(1));
//
//        $userProvider->updateCanonical($user);
//        $userProvider->updateUser($user, true);

        $form = $this->createForm(new LoginType($request));
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/logout", name="tutto_logout")
     * @Method({"GET"})
     */
    public function logoutAction() { }

    /**
     * @Route("/check_path", name="tutto_check_path")
     * @Method({"POST"})
     */
    public function checkAction() { }
}
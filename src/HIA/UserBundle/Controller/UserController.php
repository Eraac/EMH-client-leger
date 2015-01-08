<?php

namespace HIA\UserBundle\Controller;

use HIA\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Util\SecureRandom;

class UserController extends Controller
{
    /**
     * @Route("/login", name="HIAUserLogin")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('HIACoreIndex'));
        }

        $session = $request->getSession();

        // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            // Valeur du précédent nom d'utilisateur entré par l'internaute
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * @Route("/account/settings", name="HIAUserChangeSettings")
     * @Template()
     */
    public function changeSettingsAction(Request $request)
    {
        // On récupère l'utilisateur courant
        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->get('form.factory')->createBuilder();

        $form->add('lastName', 'text', array('label' => 'Nom', 'data' => $user->getName()));
        $form->add('firstName', 'text', array('label' => 'Prénom', 'data' => $user->getFirstName()));
        $form->add('password', 'repeated', array('label' => 'Mot de passe', 'required' => false, 'type' => 'password',
                                                'first_options'  => array('label' => 'Mot de passe', 'attr' => array('help_block' => "Laissez vide pour ne rien changer")),
                                                'second_options' => array('label' => 'Confirmation')
                                               ));
        $form->add('Modifier', 'submit', array('attr' => array('class' => "pull-right btn-warning")));

        $form = $form->getForm();

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $setting = $form->getData();

            $user->setName($setting['lastName'])
                 ->setFirstName($setting['firstName']);

            if (null !== $setting['password'])
            {
                $factory = $this->container->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);

                $user->setPassword($encoder->encodePassword($setting['password'], $user->getSalt()));
            }

            // On indique à l'utilisateur que le changement à fonctionné
            $this->get('session')->getFlashBag()->add(
                'success',
                'Modification effectuée'
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

        }

        return array('form' => $form->createView(), 'user' => $user);
    }

    /**
     * @Route("/account/forgetPassword", name="HIAUserForgetPassword")
     * @Template()
     */
    public function forgetPasswordAction(Request $request)
    {
        $error = null;
        $email = $request->request->get('email');

        if (null !== $email)
        {
            $manager = $this->getDoctrine()->getManager();
            $repository = $manager->getRepository('HIAUserBundle:User');

            $user = $repository->findOneByUsername($email);

            if (null != $user)
            {
                $generator = new SecureRandom();
                $newPassword = bin2hex($generator->nextBytes(10));

                $factory = $this->container->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);

                $user->setPassword($encoder->encodePassword($newPassword, $user->getSalt()));

                $manager->persist($user);
                $manager->flush();

                $message = \Swift_Message::newInstance()
                        ->setSubject('Nouveau mot de passe')
                        ->setFrom('contact@hia.com')
                        ->setTo($email)
                        ->setBody("Votre nouveau mot de passe : " . $newPassword);

                $this->get('mailer')->send($message);

                // On indique à l'utilisateur que l'enregistrement à fonctionner
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Nouveau mot de passe envoyé'
                );

                $url = $this->get('router')->generate('HIACoreIndex');

                return $this->redirect($url);
            }
            else
            {
                $error = "Email inexistant";
            }
        }


        return array("last_email" => $email, "error" => $error);
    }
}

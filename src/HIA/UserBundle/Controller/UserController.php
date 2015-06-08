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
     * @Route("/logout", name="HIAUserLogout")
     * @Template()
     */
    public function logoutAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->get('hia_session.record')->recordLogout($user);

        // Nettoye la session
        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirect($this->generateUrl('HIAUserLogin'));
    }

    /**
     * @Route("/account/settings", name="HIAUserChangeSettings")
     * @Template()
     */
    public function changeSettingsAction(Request $request)
    {
        // On récupère l'utilisateur courant
        $user = $this->get('security.token_storage')->getToken()->getUser();

        // On récupère le contructeur de formulaire
        $form = $this->get('form.factory')->createBuilder();

        // On ajoute un champs pour le nom
        $form->add('lastName', 'text', array('label' => 'Nom', 'data' => $user->getName()));

        // On ajoute un champs pour le prénom
        $form->add('firstName', 'text', array('label' => 'Prénom', 'data' => $user->getFirstName()));

        // On ajoute un champs pour le nouveau mot de passe de l'utilisateur
        $form->add('password', 'repeated', array('label' => 'Mot de passe', 'required' => false, 'type' => 'password',
                                                'first_options'  => array('label' => 'Mot de passe', 'attr' => array('help_block' => "Laissez vide pour ne rien changer")),
                                                'second_options' => array('label' => 'Confirmation')
                                               ));

        // On ajoute un bouton pour valider
        $form->add('Modifier', 'submit', array('attr' => array('class' => "pull-right btn-warning")));

        // On récupère le formulaire
        $form = $form->getForm();

        // On donne la requête au formulaire
        $form->handleRequest($request);

        // Si le formulaire envoyé est valide
        if ($form->isValid())
        {
            // On récupère les informations
            $setting = $form->getData();

            // On modifie l'utilisateur courant avec les nouvelles informations
            $user->setName($setting['lastName'])
                 ->setFirstName($setting['firstName']);

            // Si le champs mot de passe n'est pas vide
            if (null !== $setting['password'])
            {
                // On récupère le service qui crypte les mots de passe
                $encoder = $this->container->get('security.password_encoder');

                // On encode le mot de passe
                $password = $encoder->encodePassword($user, $setting['password']);

                // On modifie le mot de passe de l'utilisateur
                $user->setPassword($password);
            }

            // On indique à l'utilisateur que le changement à fonctionné
            $this->get('session')->getFlashBag()->add(
                'success',
                'Modification effectuée'
            );

            // On applique les changements en base de données
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

        // On récupère l'email envoyé en POST
        $email = $request->request->get('email', null);

        // Si l'email est vide
        if (null !== $email)
        {
            // On récupère le manager des entités
            $manager = $this->getDoctrine()->getManager();
            $repository = $manager->getRepository('HIAUserBundle:User');

            // On récupère l'utilisateur lié à cette email
            $user = $repository->findOneByUsername($email);

            // Si l'utilisateur est null
            if (null !== $user)
            {
                // On génére un nouveau mot de passe aléatoire
                $generator = new SecureRandom();
                $newPassword = bin2hex($generator->nextBytes(10));

                // On récupère le service qui crypte les mots de passe
                $encoder = $this->container->get('security.password_encoder');

                // On encode le mot de passe
                $password = $encoder->encodePassword($user, $newPassword);

                // On modifie le mot de passe de l'utilisateur
                $user->setPassword($password);

                // On applique les changements en base de données
                $manager->persist($user);
                $manager->flush();

                // On créer un email pour lui envoyer le nouveau mot de passe
                $message = \Swift_Message::newInstance()
                        ->setSubject('Nouveau mot de passe')
                        ->setFrom('contact@hia.com')
                        ->setTo($email)
                        ->setBody("Votre nouveau mot de passe : " . $newPassword);

                // On récupère le service qui envoi des email
                $this->get('mailer')->send($message);

                // On indique à l'utilisateur que l'enregistrement à fonctionner
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Nouveau mot de passe envoyé'
                );

                // On récupère l'URL lié à la route HIACoreIndex
                $url = $this->get('router')->generate('HIACoreIndex');

                // On redirige l'utilisateur
                return $this->redirect($url);
            }
            else
            {
                // On indique que l'email est inexistant
                $error = "Email inexistant";
            }
        }

        return array("last_email" => $email, "error" => $error);
    }
}

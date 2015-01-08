<?php

namespace HIA\FormBundle\Controller;

use HIA\FormBundle\Entity\FieldConstraint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use HIA\FormBundle\Entity\Registration;
use HIA\FormBundle\Entity\Register;
use HIA\FormBundle\Entity\Form;
use HIA\FormBundle\Entity\Field;
use HIA\FormBundle\FormBuilder;

class FormController extends Controller
{
    /**
     * @Route("/form/{slug}", name="HIAFormUse")
     * @ParamConverter("HIA\FormBundle\Entity\Form", options={"mapping": {"slug": "slug"}})
     * @Template()
     */
    public function useFormAction(Form $form, Request $request) // TODO call method repository plus opti
    {
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        $checkAccess = $this->get('hia_checkaccess.hia_checkaccess');

        if (!$checkAccess->canUse($idUser, $form->getId()))
        {
            throw new AccessDeniedException("Vous n'avez pas le droit d'accèder à ce formulaire");
        }

        // Récupère le service qui construit les formulaires
        $formBuilder = $this->get('hia_formbuilder.formbuilder');

        // On récupère le formulaire construit
        $htmlForm = $formBuilder->buildForm($form)->getForm();

        // On donne la requête au formulaire
        $htmlForm->handleRequest($request);

        // Le formulaire est valide
        if ($htmlForm->isValid())
        {
            // On recupère le service pour convertir les données en string
            $converterToString = $this->get('hia_convert.tostring');

            // On récupère le manager des entités
            $manager = $this->getDoctrine()->getManager();

            // On récupère l'utilisateur courant
            $user = $this->get('security.context')->getToken()->getUser();

            // On instancie une nouvelle inscription
            $registration = new Registration();
            $registration->setForm($form)                               // On lui indique le formulaire correspondant
                         ->setRegistrationDate(new \DateTime())         // On met la date de soumission
                         ->setStatus(Registration::$_STATUS['PENDING'])  // On indique le statut de l'enregistrement
                         ->setUserSubmit($user);                        // On indique l'utilisateur qui à soumit

            // On récupère les informations envoyées par le formulaire
            $datas = $htmlForm->getData();

            // On récupere le repository pour Field
            $repo = $manager->getRepository('HIAFormBundle:Field');

            // On parcourt la liste des informations envoyées par l'utilisateur
            foreach($datas as $key => $data)
            {
                if (empty($data))
                {
                    continue;
                }

                if ($key == "Remarque")
                {
                    $registration->setUserComment($data);
                    continue;
                }

                // On récupère le field correspondant à la données envoyées
                $field = $repo->getField($form->getId(), $key);

                // Si le field est valide
                if ($field)
                {
                    $constraints = $field->getFieldConstraints();

                    $isUserPassword = false;

                    foreach($constraints as $constraint)
                        if (FieldConstraint::$_TYPES['USERPASSWORD'] === $constraint->getType())
                            $isUserPassword = true;

                    if (!$isUserPassword)
                    {
                        $isChoice = (Field::$_TYPES['RADIO'] == $field->getType()) ? true : false;

                        // On créer un nouvel enregistrement
                        $register = new Register();
                        $register->setData($converterToString->convertToString($data, $isChoice))  // On lui assigne les informations
                            ->setField($field)                                  // On lui indique le field correspondant
                            ->setRegistration($registration);                   // On lui indique l'inscription correspondant

                        $manager->persist($register);   // On persiste l'enregistrement dans la base de données
                    }
                }
                else
                {
                    throw new \Exception("Un des champs n'est pas valide");
                }
            }

            // On indique à l'utilisateur que l'enregistrement à fonctionner
            $this->get('session')->getFlashBag()->add(
                'success',
                'Formulaire enregistré'
            );

            // On enregistre en base de données l'inscription
            $manager->persist($registration);

            // On valide les changements fait à la base de données
            $manager->flush();

            $url = $this->get('router')->generate('HIACoreIndex');

            return $this->redirect($url);
        }

        return array("form" => $htmlForm->createView(), "formInfo" => $form);
    }

    /**
     * @Route("/read/{id}", name="HIAFormReadRegistration") // TODO Choisir la requete pour optimiser
     * @Template()
     */
    public function readRegistrationAction(Registration $registration)
    {
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        $checkAccess = $this->get('hia_checkaccess.hia_checkaccess');

        if (!$checkAccess->canRead($idUser, $registration->getId()))
        {
            throw new AccessDeniedException("Vous n'avez pas le droit de lire cette enregistrement");
        }

        return array("registration" => $registration);
    }

    /**
     * @Route("/valid/{id}/{status}", name="HIAFormValidRegistration")
     * @ParamConverter("HIA\FormBundle\Entity\Registration", options={"mapping": {"slug": "slug"}})
     * @Template()
     */
    public function validRegistrationAction(Registration $registration, $status)
    {
        if ($status < 2 OR $status > 4)
        {
            $response = new Response("Code inconnu");
            $response->setStatusCode(500);

            return $response;
        }

        if ($registration->getStatus() != Registration::$_STATUS['PENDING'])
        {
            $response = new Response("Cette enregistrement est déjà validé");
            $response->setStatusCode(500);

            return $response;
        }

        $formStatut = $registration->getForm()->getStatus();

        if ( !(($formStatut == Form::$_STATUS['DEMAND'] AND ($status == Registration::$_STATUS['ACCEPT'] OR $status == Registration::$_STATUS['REFUSE']))
            OR
              $formStatut != Form::$_STATUS['DEMAND'] AND $status == Registration::$_STATUS['VALIDATE']))
        {
            $response = new Response("Action impossible");
            $response->setStatusCode(500);

            return $response;
        }

        $user = $this->get('security.context')->getToken()->getUser();
        $idUser = $user->getId();

        $checkAccess = $this->get('hia_checkaccess.hia_checkaccess');

        if (!$checkAccess->canRead($idUser, $registration->getId()) OR $registration->getUserSubmit()->getId() == $idUser)
        {
            throw new AccessDeniedException("Vous n'avez pas le droit de modifier le statut de l'enregistrement");
        }

        $registration->setStatus($status);
        $registration->setUserValidate($user);
        $registration->setValidationDate(new \DateTime());

        $manager = $this->getDoctrine()->getManager();

        $manager->persist($registration);
        $manager->flush();

        // On indique à l'utilisateur que là modification à fonctionné
        $this->get('session')->getFlashBag()->add(
            'success',
            'Enregistrement modifié'
        );

        $url = $this->get('router')->generate('HIACoreIndex');

        return $this->redirect($url);
    }

    /**
     * @Route("/list/unread", name="HIAFormlistUnreadForm")
     * @Template()
     */
    public function listUnreadFormAction()
    {
        return array();
    }

    /**
     * @Route("/list/read", name="HIAFormlistReadForm")
     * @Template()
     */
    public function listReadFormAction()
    {
        return array();
    }
}

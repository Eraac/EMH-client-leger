<?php

namespace HIA\FormBundle\Controller;

use HIA\FormBundle\Entity\FieldConstraint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\NotFoundException;
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
     * @Template()
     */
    public function useFormAction($slug, Request $request)
    {
        // On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();

        // On récupère le formulaire
        $form = $manager->getRepository('HIAFormBundle:Form')->getCompleteForm($slug);

        // Si le formulaire n'est pas trouvé
        if (null === $form)
        {
            // On lève une exception
            throw $this->createNotFoundException("Le formulaire n'est pas trouvé");
        }

        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le service pour vérifier l'accès
        $checkAccess = $this->get('hia_checkaccess.hia_checkaccess');

        // Si l'utilisateur n'a pas l'accès
        if (!$checkAccess->canUse($idUser, $form->getId()))
        {
            // On léve une exceptions
            throw new AccessDeniedException("Vous n'avez pas le droit d'accèder à ce formulaire");
        }

        // Récupère le service qui construit les formulaires
        $formBuilder = $this->get('hia_formbuilder.formbuilder');

        // On récupère le formulaire construit
        $htmlForm = $formBuilder->buildForm($form)->getForm();

        // On donne la requête au formulaire
        $htmlForm->handleRequest($request);

        // Si le formulaire envoyé est valide
        if ($htmlForm->isValid())
        {
            // On recupère le service pour convertir les données en string
            $converterToString = $this->get('hia_convert.tostring');

            // On récupère l'utilisateur courant
            $user = $this->get('security.context')->getToken()->getUser();

            // On instancie une nouvelle inscription
            $registration = new Registration();
            $registration->setForm($form)                           // On lui indique le formulaire correspondant
                         ->setRegistrationDate(new \DateTime())     // On met la date de soumission
                         ->setStatus(Registration::$_STATUS['NEW']) // On indique le statut de l'enregistrement
                         ->setUserSubmit($user);                    // On indique l'utilisateur qui à soumit

            // On récupère les informations envoyées par le formulaire
            $datas = $htmlForm->getData();

            // On récupere le repository pour Field
            $repo = $manager->getRepository('HIAFormBundle:Field');

            // On parcourt la liste des informations envoyées par l'utilisateur
            foreach($datas as $key => $data)
            {
                // Si l'information est vide on passe
                if (empty($data))
                {
                    continue; // Passe au prochain
                }

                // Si la clé est remarque
                if ($key == "remarque")
                {
                    // On l'ajoute dans l'enregistrement
                    $registration->setUserComment($data);

                    // On passe au prochain
                    continue;
                }

                // On récupère le field correspondant à la données envoyées
                $field = $repo->getField($form->getId(), $key);

                // Si le field est valide
                if ($field)
                {
                    // On récupère les contraintes du formulaire
                    $constraints = $field->getFieldConstraints();

                    // Si le champs possède la contrainte 'USERPASSWORD'
                    $isUserPassword = false;

                    // On parcourt les contraintes du champs
                    foreach($constraints as $constraint)
                    {
                        // Si le champs à la contrainte 'USERPASSWORD'
                        if (FieldConstraint::$_TYPES['USERPASSWORD'] === $constraint->getType())
                        {
                            $isUserPassword = true; // On l'indique
                            break; // On quitte la boucle
                        }
                    }

                    // Si le champs n'a pas la contrainte USERPASSER
                    if (!$isUserPassword)
                    {
                        // On regarde si le champs est un choix
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
                    // On lève une exception si un champs n'est pas valide
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

            // On récupère l'URL pour la route HIACoreIndex
            $url = $this->get('router')->generate('HIACoreIndex');

            // On redirige l'utilisateur vers la route HIACoreIndex
            return $this->redirect($url);
        }

        return array("form" => $htmlForm->createView(), "formInfo" => $form);
    }

    /**
     * @Route("/read/{id}", name="HIAFormReadRegistration")
     * @Template()
     */
    public function readRegistrationAction($id)
    {
        // On récupere l'enregistrement
        $registration = $this->getDoctrine()->getManager()->getRepository('HIAFormBundle:Registration')->getCompleteRegistration($id);

        // Si l'enregistrement n'est pas trouvé
        if (null === $registration)
        {
            // On lève une exception
            throw $this->createNotFoundException("L'enregistrement n'est pas trouvé");
        }

        // On récupère l'id du l'utilisateur
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le service qui vérifie les accès
        $checkAccess = $this->get('hia_checkaccess.hia_checkaccess');

        // Si l'utilisateur ne peut pas lire l'enregistrement
        if (!$checkAccess->canRead($idUser, $registration->getId()))
        {
            // On lève une exception
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
        // TODO Vérifier page 500 pour ici

        // On récupère l'utilisateur courant
        $user = $this->get('security.context')->getToken()->getUser();

        // On récupère l'id du l'utilisateur
        $idUser = $user->getId();

        // Si le status envoyé ne correspond pas à une code connu
        if ($status != Registration::$_STATUS['PENDING'] AND
            $status != Registration::$_STATUS['VALIDATE'] AND
            $status != Registration::$_STATUS['ACCEPT'] AND
            $status != Registration::$_STATUS['REFUSE'])
        {
            $response = new Response("Code inconnu");
            $response->setStatusCode(500);

            return $response;
        }

        // Si l'enregistrement n'a pas en statut 'nouveau'
        if ($registration->getStatus() != Registration::$_STATUS['NEW'] AND
            $registration->getUserValidate()->getId() != $idUser)
        {
            $response = new Response("Cette enregistrement est déjà pris en charge");
            $response->setStatusCode(500);

            return $response;
        }

        // On récupère le statut du formulaire
        $formStatut = $registration->getForm()->getStatus();

        // Si le nouveau statut ne correspond pas au type de formulaire
        if (($formStatut != Form::$_STATUS['DEMAND'] AND ($status == Registration::$_STATUS['ACCEPT'] OR $status == Registration::$_STATUS['REFUSE'])
                OR
            $formStatut == Form::$_STATUS['DEMAND'] AND $status == Registration::$_STATUS['VALIDATE'])
                AND
            $status != Registration::$_STATUS['PENDING']
        )
        {
            $response = new Response("Action impossible");
            $response->setStatusCode(500);

            return $response;
        }

        // On récupère le service qui gère les autorisations d'accès aux enregistrements
        $checkAccess = $this->get('hia_checkaccess.hia_checkaccess');

        // Si l'utilisateur n'a pas accès à l'enregistrement ou si l'utilisateur est l'auteur de la soumission
        if (!$checkAccess->canRead($idUser, $registration->getId()) OR $registration->getUserSubmit()->getId() == $idUser)
        {
            throw new AccessDeniedException("Vous n'avez pas le droit de modifier le statut de l'enregistrement");
        }

        // On modifie le statue de l'enregistrement
        $registration->setStatus($status);

        // On indique qui à valider l'enregistrement
        $registration->setUserValidate($user);

        // On indique quand l'utilisateur à valider l'enregistrement
        $registration->setValidationDate(new \DateTime());

        // On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();

        // On persist l'enregistrement
        $manager->persist($registration);

        // On applique les changements en base de données
        $manager->flush();

        // On indique à l'utilisateur que là modification à fonctionné
        $this->get('session')->getFlashBag()->add(
            'success',
            'Enregistrement modifié'
        );

        // On récupère l'URL de la route HIACoreIndex
        $url = $this->get('router')->generate('HIAFormReadRegistration', array('id' => $registration->getId()));

        // On redirige l'utilisateur
        return $this->redirect($url);
    }
}

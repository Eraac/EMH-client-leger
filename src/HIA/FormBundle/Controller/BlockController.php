<?php

namespace HIA\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use HIA\FormBundle\Entity\Registration;

class BlockController extends Controller
{
    /**
     * @Template()
     *
     * Bloc listant les soumissions des autres utilisateurs non traitées
     */
    public function unreadOtherAction($offset, $limit)
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le repository de Registration
        $repo = $this->getDoctrine()->getManager()->getRepository('HIAFormBundle:Registration');

        // On éxecute la requête
        $listUnreadSubmitByOther = $repo->listUnreadSubmitByOther($idUser, Registration::$_STATUS['PENDING'], $offset, $limit);

        return array('list' => $listUnreadSubmitByOther);
    }

    /**
     * @Template()
     *
     * Bloc affichant la liste des enregistrements traités de l'utilisateur courant
     */
    public function readUserAction($offset, $limit)
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le repository de Registration
        $repo = $this->getDoctrine()->getManager()->getRepository('HIAFormBundle:Registration');

        // Fait la requête SQL
        $listReadSubmitByUser = $repo->listReadSubmitByUser($idUser, Registration::$_STATUS['PENDING'], $offset, $limit);

        return array("list" => $listReadSubmitByUser);
    }

    /**
     * @Template()
     *
     * Bloc affichant les derniers formulaires utilisés par l'utilisateur courant
     */
    public function lastFormUsedAction($offset, $limit)
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();

        // On récupere le repository de l'entité Registration
        $registrationRepository = $manager->getRepository("HIAFormBundle:Registration");

        // On lance la requête
        $lastFormUsed = $registrationRepository->getLastFormUsed($idUser, $offset, $limit);

        return array('list' => $lastFormUsed);
    }

	/**
	 * @Template()
     *
     * Bloc affichant le nombre de formulaire envoyés
	 */
	public function countSubmitFormAction($idUser)
	{
		// Si l'id de l'utilisateur n'est pas donné on la prends celle de l'utilisateur courant
		if (null === $idUser)	
	        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

		// On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();

        // On récupere le repository de l'entité Registration
        $registrationRepository = $manager->getRepository("HIAFormBundle:Registration");

        // On lance la requête SQL
        $number = $registrationRepository->countSubmitForm($idUser);

		return array("number" => $number);
	}

	/**
	 * @Template()
     *
     * Bloc affichant le nombre d'enregistrement traité pour un utilisateur
	 */
	public function countValidFormAction($idUser)
	{
		// Si l'id de l'utilisateur n'est pas donné on prends celle de l'utilisateur courant
		if (null === $idUser)	
	        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

		// On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();

        // On récupere le repository de l'entité Registration
        $registrationRepository = $manager->getRepository("HIAFormBundle:Registration");

        // On lance la requête SQL
        $number = $registrationRepository->countValidForm($idUser);

		return array("number" => $number);
	}

    /**
     * Template()
     */
    public function countUnreadSubmitByOtherAction()
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le repository de Registration
        $repo = $this->getDoctrine()->getManager()->getRepository("HIAFormBundle:Registration");

        // On récupère le nombre de registration disponible pour cet utilisateur qui sont non lus
        $countUnreadSubmitByOther = $repo->countUnreadSubmitByOther($idUser);

        return $this->render(
            'HIAFormBundle:Block:countUnreadSubmitByOther.html.twig',
            array('countUnreadSubmit' => $countUnreadSubmitByOther)
        );
    }
}






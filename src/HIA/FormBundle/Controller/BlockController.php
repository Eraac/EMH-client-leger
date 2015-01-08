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

        // On récupère la liste des enregistrements des autres non lus
        $listUnreadSubmitByOther = $repo->listUnreadSubmitByOther($idUser, Registration::$_STATUS['PENDING'], $offset, $limit);

        return array('list' => $listUnreadSubmitByOther);
    }

    /**
     * @Template()
     *
     * Bloc listant les soumissions faite par l'utilisateur courant non traitées
     */
    public function unreadUserAction($offset, $limit)
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le repository de Registration
        $repo = $this->getDoctrine()->getManager()->getRepository('HIAFormBundle:Registration');

        $listUnreadSubmitByUser = $repo->listUnreadSubmitByUser($idUser, Registration::$_STATUS['PENDING'], $offset, $limit);

        return array('list' => $listUnreadSubmitByUser);
    }

    /**
     * @Template()
     *
     * Bloc affichant les soumissions des autres utilisateurs traitées
     */
    public function readOtherAction($offset, $limit)
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le repository de Registration
        $repo = $this->getDoctrine()->getManager()->getRepository('HIAFormBundle:Registration');

        // On récupère la liste des enregistrements des autres lus
        $listReadSubmitByOther = $repo->listReadSubmitByOther($idUser, Registration::$_STATUS['PENDING'], $offset, $limit);

        return array("list" => $listReadSubmitByOther);
    }

    /**
     * @Template()
     */
    public function readUserAction($offset, $limit)
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le repository de Registration
        $repo = $this->getDoctrine()->getManager()->getRepository('HIAFormBundle:Registration');

        // On récupère la liste des enregistrements de l'utilisateur courant lus
        $listReadSubmitByUser = $repo->listReadSubmitByUser($idUser, Registration::$_STATUS['PENDING'], $offset, $limit);

        return array("list" => $listReadSubmitByUser);
    }

    /**
     * @Template()
     */
    public function lastFormUsedAction($offset, $limit)
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();

        // On récupere le repository de l'entité Registration
        $registrationRepository = $manager->getRepository("HIAFormBundle:Registration");

        // Récupère les derniers formulaires utilisés
        $lastFormUsed = $registrationRepository->getLastFormUsed($idUser, $offset, $limit);

        return array('list' => $lastFormUsed);
    }

	/**
	 * @Template()
	 */
	public function countSubmitFormAction($idUser, $offset, $limit)
	{
		// Si l'id de l'utilisateur n'est pas donné on la récupère
		if (null === $idUser)	
	        $idUser = $this->get('security.context')->getToken()->getUser()->getId(); 		// On récupère l'id de l'utilisateur courant

		// On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();

        // On récupere le repository de l'entité Registration
        $registrationRepository = $manager->getRepository("HIAFormBundle:Registration");

        // Récupère les derniers formulaires utilisés
        $number = $registrationRepository->countSubmitForm($idUser, $offset, $limit);

		return array("number" => $number);
	}

	/**
	 * @Template()
	 */
	public function countValidFormAction($idUser, $offset, $limit)
	{
		// Si l'id de l'utilisateur n'est pas donné on la récupère
		if (null === $idUser)	
	        $idUser = $this->get('security.context')->getToken()->getUser()->getId(); 		// On récupère l'id de l'utilisateur courant

		// On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();

        // On récupere le repository de l'entité Registration
        $registrationRepository = $manager->getRepository("HIAFormBundle:Registration");

        // Récupère les derniers formulaires utilisés
        $number = $registrationRepository->countValidForm($idUser, $offset, $limit);

		return array("number" => $number);
	}
}






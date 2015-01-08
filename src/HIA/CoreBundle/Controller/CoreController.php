<?php

namespace HIA\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use HIA\FormBundle\Entity\Registration;

class CoreController extends Controller
{
    /**
     * @Route("/", name="HIACoreIndex")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Template()
     *
     * Compte le nombre de soumission non traitées
     */
    public function countUnreadSubmitAction()
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le repository de Registration
        $repo = $this->getDoctrine()->getManager()->getRepository("HIAFormBundle:Registration");

        // On récupère le nombre de registration disponible pour cet utilisateur qui sont non lus
        $countUnreadSubmit = $repo->countUnreadSubmit($idUser);

        return array("countUnreadSubmit" => $countUnreadSubmit);
    }
    
    /**
     * 
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
            'HIACoreBundle:Core:countUnreadSubmit.html.twig',
            array('countUnreadSubmit' => $countUnreadSubmitByOther)
        );
        
    }
        
}

<?php

namespace HIA\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

define ("ITEM_PER_PAGE", 20);

class SearchController extends Controller
{
    /**
     * @Route("/search/form", name="HIAFormSearchAjax")
     * @Method({"POST"})
     */
    public function searchFormAjaxAction(Request $request) // Uniquement si la requête est en Ajax
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.token_storage')->getToken()->getUser()->getId();

        // On récupère la valeur POST 'formName'
        $formName = $request->request->get("formName");

        // On récupère la valeur POST 'idTag'
        $idTags = $request->request->get("idTag");

        // On récupère la valeur POST 'nbForms'
        $nbForms = $request->request->get("nbForms");

        // On récupère le manager pour les entités
        $manager = $this->getDoctrine()->getManager();

        // On récupère le repository de Form
        $formRepository = $manager->getRepository("HIAFormBundle:Form");

        // On récuperer les formulaires qui répondent aux critéres
        $listForms = $formRepository->getForms($idUser, $formName, $idTags, $nbForms, ITEM_PER_PAGE);

        $response = array('forms' => $listForms);

        // On retourne une réponse en JSON
        return new JsonResponse($response);
    }

    /**
     * @Route("/search/form", name="HIAFormSearch")
     * @Method({"GET"})
     * @Template()
     */
    public function searchFormAction()
    {
        // On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();

        // On récupère le repository de Tag
        $tagRepository = $manager->getRepository("HIAFormBundle:Tag");

        // On récupère la liste des tags
        $listTags = $tagRepository->findAll();

        return array("tags" => $listTags);
    }
    
    
    /**
     * @Route("/search/registration", name="HIARegistrationSearchAjax")
     * @Method({"POST"})
     */
    public function searchRegistrationAjaxAction(Request $request) // Uniquement si la requête est en Ajax
    {
        // On récupère le nombre d'enregistrmeent
        $nbRegistrations = $request->request->get("nbRegistration", 0);

        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.token_storage')->getToken()->getUser()->getId();

        // On récupère la valeur POST 'idStatus'
        $idStatus = $request->request->get("idStatus");

        $submit = $request->request->get("submit");
        $valid = $request->request->get("valid");

        $who = array();
        
        if (is_array($submit))
        {
            $who['submitByUser'] = in_array(1, $submit);
            $who['submitByOther'] = in_array(2, $submit);
        }
        else
        {
            $who['submitByUser'] = false;
            $who['submitByOther'] = false;
        }

        if (is_array($valid))
        {
            $who['validByUser'] = in_array(1, $valid);
            $who['validByOther'] = in_array(2, $valid);
        }
        else
        {
            $who['validByUser'] = false;
            $who['validByOther'] = false;
        }

        // On récupère le manager pour les entités
        $manager = $this->getDoctrine()->getManager();

        // On récupère le repository de Form
        $registrationRepository = $manager->getRepository("HIAFormBundle:Registration");        

        // On récuperer les formulaires qui répondent aux critéres
        $listRegistrations = $registrationRepository->getRegistrations($idUser, $idStatus, $who, $nbRegistrations, ITEM_PER_PAGE);


        $response = array('registrations' => $listRegistrations);

        // On retourne une réponse en JSON
        return new JsonResponse($response);
    }

    /**
     * @Route("/search/registration", name="HIARegistrationSearch")
     * @Method({"GET"})
     * @Template()
     */
    public function searchRegistrationAction()
    {
        return array();
    }
        
}

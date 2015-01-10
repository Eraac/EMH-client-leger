<?php

namespace HIA\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

define ("ITEM_PER_PAGE", 20); // TODO [BUG] Le nombre n'est pas bon

class SearchController extends Controller
{
    /**
     * @Route("/search/form/{page}", name="HIAFormSearchAjax",
     *      requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method({"POST"})
     */
    public function searchFormAjaxAction(Request $request, $page) // Uniquement si la requête est en Ajax
    {
        // On repasse sur la page 1
        $page = 1;

        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère la valeur POST 'formName'
        $formName = $request->request->get("formName");

        // On récupère la valeur POST 'idTag'
        $idTags = $request->request->get("idTag");

        // On récupère le manager pour les entités
        $manager = $this->getDoctrine()->getManager();

        // On récupère le repository de Form
        $formRepository = $manager->getRepository("HIAFormBundle:Form");

        // On récuperer les formulaires qui répondent aux critéres
        $listForms = $formRepository->getForms($idUser, $formName, $idTags, ($page - 1) * ITEM_PER_PAGE, ITEM_PER_PAGE * $page);

        // On récupère le nombre de formulaire pour savoir si il reste des pages
        $countForm = $formRepository->countFormUserCanAccessAjax($idUser, $formName, $idTags);

        $response = array('forms' => $listForms, 'hasNext' => ($countForm > ITEM_PER_PAGE));

        // On retourne une réponse en JSON
        return new JsonResponse($response);
    }

    /**
     * @Route("/search/form/{page}", name="HIAFormSearch", requirements={"page" = "\d+"}, defaults={"page" = 1}))
     * @Method({"GET"})
     * @Template()
     */
    public function searchFormAction($page)
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();

        // On récupère le repository de Tag
        $tagRepository = $manager->getRepository("HIAFormBundle:Tag");

        // On récupère le repository de Form
        $formRepository = $manager->getRepository("HIAFormBundle:Form");

        // On récupère la liste des tags
        $listTags = $tagRepository->findAll();

        // On récupère la liste de formulaire (limité à 20)
        $listForms = $formRepository->getAll($idUser, ($page - 1) * ITEM_PER_PAGE, ITEM_PER_PAGE * $page);
        
        // On récupère le nombre de formulaire pour savoir si il reste des pages
        $countForm = $formRepository->countFormUserCanAccess($idUser);
        
        $end = ($countForm <= ITEM_PER_PAGE * $page) ? true : false;

        return array("tags" => $listTags, "forms" => $listForms, "page" => $page, "end" => $end);
    }
    
    
    /**
     * @Route("/search/registration/{page}", name="HIARegistrationSearchAjax",
     *      requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method({"POST"})
     */
    public function searchRegistrationAjaxAction(Request $request, $page) // Uniquement si la requête est en Ajax
    {
        // On retourne à la page 1
        $page = 1;

        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

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
        $listRegistrations = $registrationRepository->getRegistrations($idUser, $idStatus, $who, ($page - 1) * ITEM_PER_PAGE, ITEM_PER_PAGE * $page);

        // On récupère le nombre enregistrement pour savoir si il reste des pages
        $countForm = $registrationRepository->countRegistrationUserCanAccessAjax($idUser, $idStatus, $who);

        $response = array('registrations' => $listRegistrations, 'hasNext' => ($countForm > ITEM_PER_PAGE));

        // On retourne une réponse en JSON
        return new JsonResponse($response);
    }

    /**
     * @Route("/search/registration/{page}", name="HIARegistrationSearch", requirements={"page" = "\d+"}, defaults={"page" = 1}))
     * @Method({"GET"})
     * @Template()
     */
    public function searchRegistrationAction($page)
    {
        // On récupère l'id de l'utilisateur courant
        $idUser = $this->get('security.context')->getToken()->getUser()->getId();

        // On récupère le manager des entités
        $manager = $this->getDoctrine()->getManager();        

        // On récupère le repository de registration
        $registrationRepository = $manager->getRepository("HIAFormBundle:Registration");

        // On récupère la liste de registration (limité à 20)
        $listRegistrations = $registrationRepository->getAll($idUser, ($page - 1) * ITEM_PER_PAGE, ITEM_PER_PAGE * $page);
        
        // On récupère le nombre enregistrement pour savoir si il reste des pages
        $countForm = $registrationRepository->countRegistrationUserCanAccess($idUser);
        
        $end = ($countForm <= ITEM_PER_PAGE * $page) ? true : false;

        return array("registrations" => $listRegistrations, "page" => $page, "end" => $end);
    }
        
}

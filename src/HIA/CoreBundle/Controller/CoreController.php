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
}

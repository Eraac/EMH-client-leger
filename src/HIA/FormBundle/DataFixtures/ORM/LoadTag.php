<?php

namespace HIA\FormBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HIA\FormBundle\Entity\Form;
use HIA\FormBundle\Entity\Field;
use HIA\FormBundle\Entity\Tag;
use HIA\UserBundle\Entity\User;
use HIA\UserBundle\Entity\UserGroup;

class LoadTag implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $repoForm = $manager->getRepository("HIAFormBundle:Form");
        $demandeMorphine    = $repoForm->find(1);
        $demandeBequilles   = $repoForm->find(2);
        $avisHopital        = $repoForm->find(3);

        $tagNames = array("Demande", "Urgent", "Informatif", "Test");
        $listTags = array();

        foreach($tagNames as $tagName)
        {
            $tag = new Tag();
            $tag->setName($tagName);
            $listTags[] = $tag;
            $manager->persist($tag);
        }

        $demandeMorphine->addTag($listTags[0]); // Demande
        $demandeMorphine->addTag($listTags[1]); // Urgent
        $demandeBequilles->addTag($listTags[0]); // Demande
        $avisHopital->addTag($listTags[2]); // Informatif

        $allForms = $repoForm->findAll();

        foreach($allForms as $form)
        {
            $form->addTag($listTags[3]);
        }

        // On déclenche l'enregistrement
        $manager->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}
<?php

namespace HIA\FormBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HIA\FormBundle\Entity\FieldConstraint;
use HIA\FormBundle\Entity\Param;

class LoadConstraint implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /*$repoField = $manager->getRepository("HIAFormBundle:Field");
        $pourquoiMorphine   = $repoField->find(1);
        $quantiteMorphine   = $repoField->find(2);
        $pourquoiBequille   = $repoField->find(3);
        $debutBequille      = $repoField->find(4);
        $finBequille        = $repoField->find(5);
        $avisHopital        = $repoField->find(6);

        $constraintRegex = new FieldConstraint();
            $constraintRegex->setType(FieldConstraint::$_TYPES['REGEX']);
            $constraintRegex->setFields($avisHopital);
            $pourquoiMorphine->addFieldConstraint($constraintRegex);
            $pourquoiBequille->addFieldConstraint($constraintRegex);

        $constraintDate = new FieldConstraint();
            $constraintDate->setType(FieldConstraint::$_TYPES['DATE']);
            $constraintDate->setFields($debutBequille);

        $constraintRange = new FieldConstraint();
            $constraintRange->setType(FieldConstraint::$_TYPES['RANGE']);
            $constraintRange->setFields($quantiteMorphine);

        $constrainMin = new FieldConstraint();
            $constrainMin->setType(FieldConstraint::$_TYPES['HIGHER']);
            $constrainMin->setFields($finBequille);

        $regexParam = new Param();
            $regexParam->setValue("/[a-zA-Z]+/");
            $regexParam->setFieldConstraint($constraintRegex);

        $minParam = new Param();
            $minParam->setValue("1");
            $minParam->setFieldConstraint($constraintRange);

        $min2Param = new Param();
            $min2Param->setValue("0");
            $min2Param->setFieldConstraint($constrainMin);

        $maxParam = new Param();
            $maxParam->setValue("20");
            $maxParam->setFieldConstraint($constraintRange);

        $manager->persist($constraintRegex);
        $manager->persist($constraintDate);
        $manager->persist($constraintRange);
        $manager->persist($constrainMin);
        $manager->persist($regexParam);
        $manager->persist($minParam);
        $manager->persist($min2Param);
        $manager->persist($maxParam);

        // On déclenche l'enregistrement
        $manager->flush();*/
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
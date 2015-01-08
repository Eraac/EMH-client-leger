<?php

namespace HIA\FormBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HIA\FormBundle\Entity\Form;
use HIA\FormBundle\Entity\Registration;
use HIA\FormBundle\Entity\Register;
use HIA\FormBundle\Entity\Field;
use HIA\UserBundle\Entity\User;

class LoadRegistration implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /*$repoUser = $manager->getRepository("HIAUserBundle:User");
        $author1 = $repoUser->find(2); // Luisaile
        $author2 = $repoUser->find(3); // Tyreal
        $user    = $repoUser->find(1); // Arthas

        $repoForm = $manager->getRepository("HIAFormBundle:Form");
        $form1   = $repoForm->find(1);
        $form2   = $repoForm->find(2);
        $form3   = $repoForm->find(3);
        $form4   = $repoForm->find(4);

        $repoField = $manager->getRepository("HIAFormBundle:Field");
        $field1  = $repoField->find(1);
        $field2  = $repoField->find(2);

        $registrationStatus             = array(Registration::$STATUS['VALIDATE'], Registration::$STATUS['PENDING'], Registration::$STATUS['PENDING'], Registration::$STATUS['PENDING'], Registration::$STATUS['REFUSE']);
        $registrationRegistrationDate   = array(new \DateTime, new \DateTime, new \DateTime, new \DateTime, new \DateTime);
        $registrationUserComment        = array("Sans commentaire", "Mon commentaire", "D'accord", "LOL", "rien");
        $registrationValidationDate     = array(new \DateTime, null, null, null, new \DateTime);
        $registrationForm               = array($form1, $form1, $form3, $form2, $form4);
        $registrationUserValidate       = array($user, null, null, null, $author1);
        $registrationUserSubmit         = array($author1 , $author1, $author1, $author2, $user);
        $listRegistrations              = array();

        $registrationMethodName = array("Status", "RegistrationDate", "UserComment", "ValidationDate", "Form", "UserValidate", "UserSubmit");

        $registerData           = array("Paul", "Marc", "Dupont", "Marie", "Marine", "Kévin", "Joann", "Hadrien", "Guillaume", "Lucas");
        $registerField          = array($field1, $field2, $field1, $field2, $field1, $field2, $field1, $field2, $field1, $field2);
        $listRegisters          = array();

        $registerMethodName = array("Data", "Field");

        // On hydraye les registrations
        for($i = 0; $i < 5; $i++)
        {
            $registration = new Registration();

            foreach($registrationMethodName as $method)
            {
                $setMethod  = "set" . $method;
                $varName    = "registration" . $method;
                $arrayName  = $$varName;

                if (method_exists($registration, $setMethod) && isset($$varName))
                    $registration->$setMethod($arrayName[$i]);
            }

            $listRegistrations[] = $registration;
            $manager->persist($registration);
        }

        // On hydraye les register
        for($i = 0; $i < 10; $i++)
        {
            $register = new Register();

            foreach($registerMethodName as $method)
            {
                $setMethod  = "set" . $method;
                $varName    = "register" . $method;
                $arrayName  = $$varName;

                if (method_exists($register, $setMethod) && isset($$varName) && $arrayName[$i] != null)
                    $register->$setMethod($arrayName[$i]);
            }

            $listRegisters[] = $register;
            $manager->persist($register);
        }

        foreach($listRegistrations as $key => $registration)
        {
            $registration->addRegister($listRegisters[$key * 2]);
            $registration->addRegister($listRegisters[($key * 2) + 1]);
        }

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
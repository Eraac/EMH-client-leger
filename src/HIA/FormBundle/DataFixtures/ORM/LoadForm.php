<?php

namespace HIA\FormBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HIA\FormBundle\Entity\Form;
use HIA\FormBundle\Entity\Field;
use HIA\FormBundle\Entity\DefaultValue;
use HIA\UserBundle\Entity\User;
use HIA\UserBundle\Entity\UserGroup;

class LoadForm implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $repoUser = $manager->getRepository("HIAUserBundle:User");
        $userAdministratif  = $repoUser->find(2);
        $userAll = $repoUser->find(4);

        $repoGroup = $manager->getRepository("HIAUserBundle:UserGroup");
        $groupMedecin           = $repoGroup->find(1);
        $groupAdministration    = $repoGroup->find(2);
        $groupPatient           = $repoGroup->find(3);

        $formName = array(
            "Demande : Morphine",
            "Demande : Paire de béquille",
            "Avis hôpital",
            "TEST"
        );

        $formDescription = array(
            "Permet de faire une demande de morphine pour un(e) patient(e)",
            "Permet de demander une paire de béquille pour un(e) patient(e)",
            "Merci de donner un avis sur l'hôpital (personnel soignant, temps d'attente, ...)",
            "FORM DE TEST"
        );

        $formInfo = array(
            null,
            "Un médecin s'occupera des les livrer une fois l'enregistrement validé",
            "Nous utiliserons les informations pour améliorer nos services",
            "Je test les listes"
        );

        $formImportant = array(
            "Attention à votre quota de morphine",
            "Les béquilles devront être rendu dès que possible",
            null,
            "Je suis TEMPORAIRE"
        );

        $formColor = array("e74c3c", "3498db", "095503", "424242");

        $formStatus         = array(Form::$_STATUS['DEMAND'], Form::$_STATUS['DEMAND'], Form::$_STATUS['ENABLE'], Form::$_STATUS['ENABLE']);
        $formDateCreate     = array(new \DateTime, new \DateTime, new \DateTime, new \DateTime);
        $formAuthor         = array($userAdministratif, $userAdministratif, $userAdministratif, $userAll);

        $listForms          = array(); // Stocke la liste des formulaires

        $formMethodName = array("Name", "Description", "Info", "Important", "Color", "Status", "DateCreate", "Author");

        $fieldType = array(
            Field::$_TYPES['TEXTAREA'],   // Form 1
            Field::$_TYPES['NUMBER'],     // Form 1
            Field::$_TYPES['TEXTAREA'],   // Form 2
            Field::$_TYPES['DATE'],       // Form 2
            Field::$_TYPES['NUMBER'],     // Form 2
            Field::$_TYPES['TEXTAREA'],   // Form 3
            Field::$_TYPES['RADIO'],      // Form 4 not multiple
            Field::$_TYPES['RADIO']       // Form 4 multiple
        );

        $fieldMultiple = array(false, false, false, false, false, false, false, true);
        $fieldIsRequired = array(true, true, true, true, false, true, true, true);

        $fieldLabel = array(
            "Raison",
            "La quantité",
            "Pourquoi ?",
            "Date de début",
            "Nombre de jours",
            "Commentaires",
            "Non multiple",
            "Multiple"
        );

        $fieldPlaceholder = array(
            "Entrez les explications",
            null,
            "Entrez les explications",
            null,
            null,
            "Merci pour votre commentaire",
            null, 
            null
        );

        $fieldHelpText = array(
            "Expliquer pourquoi vous avez besoin de morphine",
            "Quantité en ml",
            "Expliquer pourquoi vous avez besoin de béquilles",
            "La date de début d'utilisation des béquilles (ex. aujourd'hui)",
            "Si la date n'est pas connu, laissez vide",
            null,
            null,
            null
        );

        $listFields = array();

        $fieldMethodName = array("Type", "Multiple", "Label", "IsRequired", "Placeholder", "HelpText");

        // On hydrate les forms
        for($i = 0; $i < 4; $i++)
        {
            $form = new Form();

            foreach($formMethodName as $method)
            {
                $setMethod  = "set" . $method;
                $varName    = "form" . $method;
                $arrayName  = $$varName;

                if (method_exists($form, $setMethod) && isset($$varName))
                    $form->$setMethod($arrayName[$i]);
            }

            $listForms[] = $form;
        }

        // On hydrate les fields
        for($i = 0; $i < 8; $i++)
        {
            $field = new Field();

            foreach($fieldMethodName as $method)
            {
                $setMethod  = "set" . $method;
                $varName    = "field" . $method;
                $arrayName  = $$varName;

                if (method_exists($field, $setMethod) && isset($$varName))
                    $field->$setMethod($arrayName[$i]);
            }

            $listFields[] = $field;
        }

        // On creer les valeurs par defaut des deux champs radio
        $defaults = array(
            "Je suis le numero 1",
            "Je suis le numero 2",
            "Je suis le numero 3",
            "Je suis le numero 4",
            "Je suis le numero 5",
            "Je suis le numero 6"
        );
        
        foreach ($defaults as $key => $default)
        {
            $defaultValue = new DefaultValue();
            $defaultValue->setValue($default);
            
            if (2 >= $key)
                $defaultValue->setField($listFields[6]);
            else
                $defaultValue->setField($listFields[7]);
            
            $manager->persist($defaultValue);
        }
        
        // On ajoute les fields dans les forms

        $listFields[0]->setForm($listForms[0]);
        $listFields[1]->setForm($listForms[0]);
        $listFields[2]->setForm($listForms[1]);
        $listFields[3]->setForm($listForms[1]);
        $listFields[4]->setForm($listForms[1]);
        $listFields[5]->setForm($listForms[2]);
        $listFields[6]->setForm($listForms[3]);
        $listFields[7]->setForm($listForms[3]);

        foreach($listForms as $form)
            $manager->persist($form);

        foreach($listFields as $field)
            $manager->persist($field);


        $listForms[0]->addReader($groupAdministration);
        $listForms[0]->addWriter($groupMedecin);

        $listForms[1]->addReader($groupMedecin);
        $listForms[1]->addWriter($groupAdministration);

        $listForms[2]->addReader($groupAdministration);
        $listForms[2]->addWriter($groupPatient);
        
        $listForms[3]->addReader($groupAdministration);

        for ($i = 0; $i < 100; $i++)
        {
            $form = new Form();
            $form->setName("Form n°" . $i)
                ->setDescription("Description")
                ->setInfo("Info")
                ->setImportant("Important")
                ->setColor("222222")
                ->setStatus(Form::$_STATUS['DEMAND'])
                ->setDateCreate(new \DateTime())
                ->setAuthor($userAdministratif)
                ->addReader($groupPatient)
                ->addWriter($groupPatient);

            $manager->persist($form);
        }
        
        // On déclenche l'enregistrement
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
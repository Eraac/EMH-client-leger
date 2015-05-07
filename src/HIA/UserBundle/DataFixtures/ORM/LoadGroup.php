<?php

namespace HIA\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HIA\UserBundle\Entity\User;
use HIA\UserBundle\Entity\UserGroup;

class LoadGroup implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Les noms d'utilisateurs à créer
        /*$listNames = array('Médecin', 'Administration', 'Patient');

        $repo = $manager->getRepository('HIAUserBundle:User');

        $user1 = $repo->find(1); // Medecin
        $user2 = $repo->find(2); // Administratif
        $user3 = $repo->find(3); // Patient
        $user4 = $repo->find(4); // All

        foreach($listNames as $key => $name)
        {
            $group = new UserGroup();

            $group->setName($name);

            $group->addUser($user4);

            if ($key == 0)
                $group->addUser($user1);
            else if ($key == 1)
                $group->addUser($user2);
            else if ($key == 2)
                $group->addUser($user3);

            $manager->persist($group);
        }

        // On déclenche l'enregistrement
        $manager->flush();*/
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
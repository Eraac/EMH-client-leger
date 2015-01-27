<?php

namespace HIA\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HIA\UserBundle\Entity\User;
use HIA\UserBundle\Entity\UserGroup;

class LoadUser implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // Les noms d'utilisateurs à créer (pour le login)
        $listUsernames = array(
            'medecin@hia.com',
            'administratif@hia.com',
            'patient@gmail.com',
            'all@all.com'
        );
        
        $listPasswords = array(
            'medecin',
            'administratif',
            'patient',
            'all'
        );
        
        $listNames = array(
            'RYAN',
            'ROBERTS',
            'LANE',
            'ALL'
        );
        
        $listFirstNames = array(
            'Carl',
            'Jeanne',
            'Perry',
            'all'
        );

        // On récupère le service qui crypte les mots de passe
        $encoder = $this->container->get('security.password_encoder');

        foreach ($listUsernames as $key => $username)
        {
            // On crée l'utilisateur
            $user = new User;

            // Le nom d'utilisateur et le mot de passe sont identiques
            $user->setUsername($username);
            $user->setName($listNames[$key]);
            $user->setFirstName($listFirstNames[$key]);
            // On ne se sert pas du sel pour l'instant
            $user->setSalt('');

            // On encode le mot de passe
            $password = $encoder->encodePassword($user, $listPasswords[$key]);

            // On modifie le mot de passe de l'utilisateur
            $user->setPassword($password);

            // On définit uniquement le role ROLE_USER qui est le role de base
            $user->setRoles(array('ROLE_USER'));

            // On le persiste
            $manager->persist($user);
        }

        // On déclenche l'enregistrement
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 0; // the order in which fixtures will be loaded
    }
}
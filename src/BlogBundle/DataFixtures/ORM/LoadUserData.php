<?php

namespace BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\User;
use BlogBundle\Entity\Role;
use BlogBundle\Entity\Theme;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userRole = new Role();
        $userRole->setRole("ROLE_ADMIN");
        $manager->persist($userRole);
        $manager->flush();

        $userAdmin = new User();
        $userAdmin->setLogin('admin@blog.fr');
        $userAdmin->setNom('Admin');
        $userAdmin->setPrenom('Admin');
        $userAdmin->setPassword('test');
        $userAdmin->addRole($userRole);
        $manager->persist($userAdmin);
        $manager->flush();

        $userRole = new Role();
        $userRole->setRole("ROLE_LECTEUR");
        $manager->persist($userRole);
        $manager->flush();

        $userRole = new Role();
        $userRole->setRole("ROLE_AUTEUR");
        $manager->persist($userRole);
        $manager->flush();

        $userRole = new Role();
        $userRole->setRole("ROLE_CRITIQUE");
        $manager->persist($userRole);
        $manager->flush();

        $manager->persist($userAdmin);
        $manager->flush();

        $thematique = new Theme();
        $thematique->setNom("Informatique");
        $manager->persist($thematique);

        $thematique = new Theme();
        $thematique->setNom("Botanique");
        $manager->persist($thematique);

        $thematique = new Theme();
        $thematique->setNom("Électronique");
        $manager->persist($thematique);

        $thematique = new Theme();
        $thematique->setNom("Mécanique");
        $manager->persist($thematique);

        $thematique = new Theme();
        $thematique->setNom("Mathématiques");
        $manager->persist($thematique);



        /*$userAdmin = new User();
        $userAdmin->setLogin('lecteur@blog.fr');
        $userAdmin->setPassword('test');

        $manager->persist($userAdmin);
        $manager->flush();

        $userAdmin = new User();
        $userAdmin->setLogin('auteur@blog.fr');
        $userAdmin->setPassword('test');

        $manager->persist($userAdmin);
        $manager->flush();

        $userAdmin = new User();
        $userAdmin->setLogin('critique@blog.fr');
        $userAdmin->setPassword('test');

        $manager->persist($userAdmin);
        $manager->flush();
        */
    }
}
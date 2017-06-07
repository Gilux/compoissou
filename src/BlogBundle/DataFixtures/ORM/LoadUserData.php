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
        $userAdmin->setAvatar("https://cdn3.iconfinder.com/data/icons/internet-and-web-4/78/internt_web_technology-13-512.png");
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
        $thematique->setDescription("Thématique consacrée à l'informatique");
        $manager->persist($thematique);

        $thematique = new Theme();
        $thematique->setNom("Botanique");
        $thematique->setDescription("SMOKE WEED EVERYDAY !!1!111");
        $manager->persist($thematique);

        $thematique = new Theme();
        $thematique->setNom("Électronique");
        $thematique->setDescription("C'est trivial.");
        $manager->persist($thematique);

        $thematique = new Theme();
        $thematique->setNom("Mécanique");
        $thematique->setDescription("Pour apprendre à être un meilleur conducteur que Claude François");
        $manager->persist($thematique);

        $thematique = new Theme();
        $thematique->setNom("Mathématiques");
        $thematique->setDescription("Ca aussi c'est trivial");
        $manager->persist($thematique);

        $manager->flush();

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
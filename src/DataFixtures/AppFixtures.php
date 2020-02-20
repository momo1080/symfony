<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Projet;
use App\Entity\Techno;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    const TECHNO = [
        'Html5',
        'Css3',
        'Javascript'
    ];

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    private function loadTechno(ObjectManager $manager){

        foreach( $this::TECHNO as $value ){
            $techno = new Techno();
            $techno->setName($value);
            $this->addReference('techno_'.$value, $techno);
            $manager->persist( $techno );
        }

        $manager->flush();
    }


    private function loadProjet(ObjectManager $manager){
        $faker = \Faker\Factory::create('fr_FR');

        for( $i=0; $i < 10; $i++){

            $projet = new Projet();
            $projet->setName( $faker->sentence($nbWords = 6,  $variableNbWords = true));
            $projet->setDescription( $faker->text($maxNbChars = 150));
            $projet->setDate( new \DateTime($faker->date($format = 'Y-m-d', $max = 'now')));
            $projet->addTechno($this->getReference('techno_'.self::TECHNO[rand(0,2)]));
            $manager->persist($projet);
        }

        $manager->flush();
    }
    
    private function loadUser(ObjectManager $manager){

        $admin = new User();
        $admin->setUsername('gitbreaker');
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'taylorSwift251'
        ));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();
    }
    
    public function load(ObjectManager $manager)
    {
        $this->loadTechno( $manager );
        $this->loadProjet($manager);
        $this->loadUser($manager);
        
        
    }
}

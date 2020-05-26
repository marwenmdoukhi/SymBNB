<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class TestFixtueres extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }



    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $roleAdmin = new Role();
        $roleAdmin->setTitle('ROLE_ADMIN');
        $manager->persist($roleAdmin);
        $userAdmin = new User();
        $userAdmin->setFirstName('Marwen')
            ->setLastName('Mdoukhi')
            ->setEmail("Marwenmdoukhi@gmail.com")
            ->setHash($this->encoder->encodePassword($userAdmin, 'admin'))
            ->setPicture("https://avatars.io/twitter/liiorC")
            ->setIntroduction($faker->paragraph(2))
            ->setDescription($faker->paragraphs(5, true))
            ->addUserRole($roleAdmin);
        $manager->persist($userAdmin);

        $users = [];
        $genders = ['male', 'female'];

        for($i = 1; $i <= 10; $i++){
            $user = new User();
            $gender = $faker->randomElement($genders);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';
            $picture .= ($gender == 'male' ? 'men/' : 'women/') . $pictureId;
            $hash= $this->encoder->encodePassword($user, 'password');


            $user->setFirstName($faker->firstname($gender))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->paragraph(2))
                ->setDescription($faker->paragraphs(5, true))
                ->setHash($hash)
                ->setPicture($picture);
            ;

            $manager->persist($user);
            $users[] = $user;
        }

        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <= 30; $i++) {
            $ad = new Ad();
            $titre=$faker->sentence();
            $coverImage=$faker->imageUrl(1000,350);
            $introduction=$faker->paragraph(2);
            $content='<p>'.join('</p><p>',$faker->paragraphs(5)) .'</p>';


            $ad->setTitre($titre)
                ->setCoverImage($coverImage )
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrix(mt_rand(40,200))
                ->setNbchambre(mt_rand(1,5))
                ->setAuthor($users[mt_rand(0, count($users) - 1)]);

            ;
            $manager->persist( $ad);

            for($j=1; $j <=mt_rand(2,5); $j++){
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence)
                    ->setAd($ad);
                $manager->persist( $image);

            }


        }

        $manager->flush();
    }
}

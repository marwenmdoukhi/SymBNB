<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TestFixtueres extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

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
                ->setNbchambre(mt_rand(1,5));
            $manager->persist( $ad);

        }

        $manager->flush();
    }
}

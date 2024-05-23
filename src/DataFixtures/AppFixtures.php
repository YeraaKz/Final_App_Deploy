<?php

namespace App\DataFixtures;

use App\Entity\ItemsCollectionCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoriesList = ['Books', 'Games', 'Coins', 'Posters', 'Others'];

        foreach ($categoriesList as $categoryName){
            $category = new ItemsCollectionCategory();
            $category->setName($categoryName);
            $manager->persist($category);
        }

        $manager->flush();
    }
}

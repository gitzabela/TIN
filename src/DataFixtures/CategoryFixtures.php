<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        'Sport',
        'Travel',
        'Social',
        'Business',
        'Health'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $category) {
            $newCategory = new Category();
            $newCategory->setName($category);
            $manager->persist($newCategory);
        }
        $manager->flush();
    }
}

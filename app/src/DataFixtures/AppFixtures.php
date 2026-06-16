<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use App\Entity\BookCategory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // categories faker
        $categoriesTitles = ['Fiction', 'Science', 'History'];

        foreach ($categoriesTitles as $categoryTitle) {
            $category = new BookCategory();
            $category
                ->setTitle($categoryTitle)
                ->setPublishedAt($faker->dateTimeBetween('-30 years', 'now'))
            ;

            $manager->persist($category);

            $categories[] = $category;
        }

        for ($i = 0; $i < 100; $i++) {
            $description = $faker->paragraph;

            $book = new Book();
            $book
                ->setTitle($faker->sentence(3))
                ->setAuthor($faker->name)
                ->setPublishedAt($faker->dateTimeBetween('-30 years', 'now'))
                ->setDescription($description)
                ->setIsbn($faker->isbn13)
                ->setCategory($faker->randomElement($categories));
            ;
            $manager->persist($book);
        }

        $manager->flush();
    }
}

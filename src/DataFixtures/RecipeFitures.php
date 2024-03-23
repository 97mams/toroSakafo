<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeFitures extends Fixture
{
    public function __construct(
        private SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr');
        $faker->addProvider(new Restaurant($faker));
        $categories = ['Dessert', 'Plat chaud', 'Entrée', 'Gôuter'];
        foreach ($categories as $c) {
            $category = (new Category())
                ->setName($c)
                ->setSlug(strtolower($this->slugger->slug($c)))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($category);
            $this->addReference($c, $category);
        }
        for ($i = 0; $i < 30; $i++) {
            $name = $faker->foodName();
            $recipe = (new Recipe)
                ->setTitle($name)
                ->setSlug(strtolower($this->slugger->slug($name)))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setContent($faker->paragraphs(10, true))
                ->setCategory($this->getReference($faker->randomElement($categories)))
                ->setDuration($faker->numberBetween(2, 60));
            $manager->persist($recipe);
        }
        $manager->flush();
    }
}

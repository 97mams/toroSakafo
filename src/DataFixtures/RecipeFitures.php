<?php

namespace App\DataFixtures;

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
        for ($i = 0; $i < 10; $i++) {
            $name = $faker->foodName();
            $recipe = (new Recipe)
                ->setTitle($name)
                ->setSlug(strtolower($this->slugger->slug($name)))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setContent($faker->paragraphs(10, true))
                ->setDuration($faker->numberBetween(2, 60));
            $manager->persist($recipe);
        }
        $manager->flush();
    }
}

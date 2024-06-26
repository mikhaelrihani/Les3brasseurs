<?php

namespace App\DataFixtures\AppFixtures;

use App\DataFixtures\AppFixtures\CoreFixtures;
use App\Entity\CookingCategory;
use App\Entity\CookingSheet;
use App\Entity\Dish;
use App\Entity\Menu;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CookingFixtures extends CoreFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {


        //! CookingCategory

        $cookingCategories = [];

        for ($i = 0; $i < 12; $i++) {
            $cookingCategory = new CookingCategory();
            $cookingCategory
                ->setName($this->faker->unique()->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $cookingCategories[] = $cookingCategory;
            $manager->persist($cookingCategory);
        }

        //! Cookingsheet

        $cookingsheets = [];

        // We fetch the pictures from the references to link them with the cookingSheets.
        $pictures = [];
        $i = 0;
        while ($this->hasReference("picture_" . $i)) {
            $picture = $this->getReference("picture_" . $i);
            $pictures[] = $picture;
            $i++;
        }

        for ($i = 0; $i < 12; $i++) {
            $cookingsheet = new CookingSheet();
            $cookingsheet
                ->setName($this->faker->unique()->word())
                ->setSlug($this->faker->unique()->slug(3, false))
                ->setCookingCategories($cookingCategories[array_rand($cookingCategories)])
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            // Associate a unique picture with each cookingsheet
            $randomIndex = array_rand($pictures);
            $selectedPicture = $pictures[$randomIndex];
            $cookingsheet->addPicture($selectedPicture);

            // Remove the selected picture from the $pictures array to ensure uniqueness
            array_splice($pictures, $randomIndex, 1);

            $cookingsheets[] = $cookingsheet;
            $manager->persist($cookingsheet);


        }

        //! Dish

        $dishes = [];

        for ($i = 0; $i < 50; $i++) {
            $dish = new Dish();
            $dish
                ->setName($this->faker->unique()->word())
                ->setSlug($this->faker->unique()->slug(3, false))
                ->setDescription($this->faker->text(1000))
                ->setComment($this->faker->text(1000))
                ->setHelpUrl($this->faker->url())
                ->setHelpText($this->faker->text(1000))
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            // A dish can have multiple cooking sheets, but each cooking sheet must be unique.
            $maxNbCookingSheet = (count($cookingsheets) > 4) ? 4 : count($cookingsheets);
            $nbCookingSheet = rand(0, $maxNbCookingSheet);

            $dishCookingSheets = [];

            for ($j = 0; $j < $nbCookingSheet; ) {
                $randomIndex = array_rand($cookingsheets);
                $selectedCookingSheet = $cookingsheets[$randomIndex];

                if (!in_array($selectedCookingSheet, $dishCookingSheets)) {
                    $dish->addCookingSheet($selectedCookingSheet);
                    $dishCookingSheets[] = $selectedCookingSheet;
                    $j++;
                }
            }

            // Each dish can have multiple dedicated pictures , but each picture must be unique.
            $maxNbPictures = (count($pictures) > 5) ? 5 : count($pictures);
            $nbPictures = rand(1, $maxNbPictures);

            $dishPictures = [];

            for ($k = 0; $k <= $nbPictures; ) {
                $randomIndex = array_rand($pictures);
                $selectedPictures = $pictures[$randomIndex];

                if (!in_array($selectedPictures, $dishPictures)) {
                    $dish->addPicture($selectedPictures);
                    $dishPictures[] = $selectedPictures;
                    $k++;
                }
            }

            // Remove the selected picture from the available list to ensure uniqueness
            array_splice($pictures, $randomIndex, 1);

            $dishes[] = $dish;
            $manager->persist($dish);
        }

        //! Menu

        // We fetch the dates from the references to link them with the inventories.
        $dates = [];
        $i = 0;
        while ($this->hasReference("date_" . $i)) {
            $date = $this->getReference("date_" . $i);
            $dates[] = $date;
            $i++;
        }

        for ($i = 0; $i < 12; $i++) {
            $menu = new Menu();
            $menu
                ->setName($this->faker->unique()->word())
                ->setSlug($this->faker->unique()->slug(3, false))
                ->setEndDateId($dates[array_rand($dates)])
                ->setStartDateId($dates[array_rand($dates)])
                ->setWeek($this->faker->numberBetween(1, 52))
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            // A menu can have multiple dishes, but each dish must be unique.
            $maxNbDishes = (count($dishes) > 5) ? 5 : count($dishes);
            $nbDishes = rand(0, $maxNbDishes);

            $menuDishes = [];

            for ($j = 0; $j <= $nbDishes; $j++) {
                $randomIndex = array_rand($dishes);
                $selectedDish = $dishes[$randomIndex];

                if (!in_array($selectedDish, $menuDishes)) {
                    $menu->addDish($selectedDish);
                    $menuDishes[] = $selectedDish;
                    $j++;
                }
            }

            $manager->persist($menu);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            MediaFixtures::class,
        ];
    }
}

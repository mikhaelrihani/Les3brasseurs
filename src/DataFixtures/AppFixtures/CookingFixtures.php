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
            $cookingCategory->setName($this->faker->word());
            $cookingCategory->setCreatedAt(new \DateTime($this->faker->date()));
            $cookingCategory->setUpdatedAt(new \DateTime($this->faker->date()));

            $cookingCategories[] = $cookingCategory;
            $manager->persist($cookingCategory);
            $this->addReference("cookingCategory_" . $i, $cookingCategory);
        }

        //! Cookingsheet

        $cookingsheets = [];

        // we retrieve the pictures from the references to be able to associate them with the cookingsheet
        $pictures = [];
        $i = 0;

        while ($this->hasReference("picture_" . $i)) {
            $picture = $this->getReference("picture_" . $i);
            $pictures[] = $picture;
            $i++;
        }

        for ($i = 0; $i < 12; $i++) {
            $cookingsheet = new CookingSheet();
            $cookingsheet->setName($this->faker->word());
            $cookingsheet->setSlug($this->faker->text(10));
            $cookingsheet->setCookingCategories($cookingCategories[array_rand($cookingCategories)]);
            $cookingsheet->setCreatedAt(new \DateTime($this->faker->date()));
            $cookingsheet->setUpdatedAt(new \DateTime($this->faker->date()));



            // each cookingsheet must have a unique picture
            $cookingsheet->addPicture($pictures[array_rand($pictures)]);
            array_splice($pictures, $picture->getId(), 1);

            $cookingsheets[] = $cookingsheet;
            $manager->persist($cookingsheet);
            $this->addReference("cookingsheet_" . $i, $cookingsheet);
        }

        //! Dish

        $dishes = [];

        for ($i = 0; $i < 50; $i++) {
            $dish = new Dish();
            $dish->setName($this->faker->word());
            $dish->setSlug($this->faker->text(10));
            $dish->setDescription($this->faker->text(1000));
            $dish->setComment($this->faker->text(1000));
            $dish->setHelpUrl($this->faker->url());
            $dish->setHelpText($this->faker->text(1000));
            $dish->setCreatedAt(new \DateTime($this->faker->date()));
            $dish->setUpdatedAt(new \DateTime($this->faker->date()));

            // A dish can have multiple cooking sheets, but each cooking sheet must be unique.
            $nbCookingSheet = rand(0, 4);
            $dishCookingSheets = [];

            for ($j = 0; $j < $nbCookingSheet; $j++) {
                $randomIndex = array_rand($cookingsheets);
                $selectedCookingSheet = $cookingsheets[$randomIndex];

                if (!in_array($selectedCookingSheet, $dishCookingSheets)) {
                    $dish->addCookingSheet($selectedCookingSheet);
                    $dishCookingSheets[] = $selectedCookingSheet;
                }
            }
            // each dish can have multiple dedicated pictures , but each picture must be unique.


            $dishPictures = [];
            for ($k = 0; $k <= rand(1, 5); $k++) {
                $randomIndex = array_rand($pictures);
                $selectedPictures = $pictures[$randomIndex];

                if (!in_array($selectedPictures, $dishPictures)) {
                    $dish->addPicture($selectedPictures);
                    $dishPictures[] = $selectedPictures;
                }
                array_splice($pictures, $randomIndex, 1);
            }

            $dishes[] = $dish;
            $manager->persist($dish);
            $this->addReference("dish_" . $i, $dish);
        }

        //! Menu

        $menus = [];

        // we retrieve the dates from the references to be able to associate them with the inventories
        $dates = [];
        $i = 0;
        while ($this->hasReference("date_" . $i)) {
            $date = $this->getReference("date_" . $i);
            $dates[] = $date;
            $i++;
        }

        for ($i = 0; $i < 12; $i++) {
            $menu = new Menu();
            $menu->setName($this->faker->word());
            $menu->setSlug($this->faker->text(10));
            $menu->setEndDateId($dates[array_rand($dates)]);
            $menu->setStartDateId($dates[array_rand($dates)]);
            $menu->setWeek($this->faker->numberBetween(1, 52));
            $menu->setCreatedAt(new \DateTime($this->faker->date()));
            $menu->setUpdatedAt(new \DateTime($this->faker->date()));

            // A menu can have multiple dishes, but each dish must be unique.
            $nbDishes = rand(0, 5);
            $menuDishes = [];

            for ($j = 0; $j <= $nbDishes; $j++) {
                $randomIndex = array_rand($dishes);
                $selectedDish = $dishes[$randomIndex];

                if (!in_array($selectedDish, $menuDishes)) {
                    $menu->addDish($selectedDish);
                    $menuDishes[] = $selectedDish;
                }
            }

            $menus[] = $menu;
            $manager->persist($menu);
            $this->addReference("menu_" . $i, $menu);
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

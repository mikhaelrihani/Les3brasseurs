<?php

namespace App\DataFixtures\AppFixtures;

use App\DataFixtures\AppFixtures\CoreFixtures;
use App\Entity\Inventory;
use App\Entity\Room;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InventoryFixtures extends CoreFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //! Room

        $rooms = [];

        for ($i = 0; $i < 8; $i++) {
            $room = new Room();
            $room->setName($this->faker->word());
            $room->setCreatedAt(new \DateTime($this->faker->date()));
            $room->setUpdatedAt(new \DateTime($this->faker->date()));

            $rooms[] = $room;
            $manager->persist($room);
            $this->addReference("room_" . $i, $room);
        }

        //! Inventory

        $inventories = [];

        // we retrieve the dates from the references to be able to associate them with the inventories
        $dates = [];
        $i = 0;
        while ($this->hasReference("date_" . $i)) {
            $date = $this->getReference("date_" . $i);
            $dates[] = $date;
            $i++;
        }

        for ($i = 0; $i < 20; $i++) {
            $inventory = new Inventory();
            $inventory->setDate($dates[array_rand($dates)]);
            $inventory->setRoom($rooms[array_rand($rooms)]);
            $inventory->setSlug($this->faker->text(10));
            $inventory->setStatus($this->faker->text(10));
            $inventory->setCreatedAt(new \DateTime($this->faker->date()));
            $inventory->setUpdatedAt(new \DateTime($this->faker->date()));

            $inventories[] = $inventory;
            $manager->persist($inventory);
            $this->addReference("inventory_" . $i, $inventory);
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

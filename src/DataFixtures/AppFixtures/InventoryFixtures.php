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
            $room->setName($this->faker->unique()->word());
            $createdAt = $this->faker->dateTimeBetween('-5 years', 'now');
            $room->setUpdatedAt($this->faker->dateTimeBetween($createdAt, 'now'));
            $room->setCreatedAt($createdAt);
            $rooms[] = $room;
            $this->addReference("room_" . $i, $room);
            $manager->persist($room);
        }

        //! Inventory

        // We fetch the dates from the references to link them with the inventories
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
            $inventory->setSlug($this->faker->slug(3, false));
            $inventory->setStatus($this->faker->text(10));
            $createdAt = $this->faker->dateTimeBetween('-5 years', 'now');
            $inventory->setUpdatedAt($this->faker->dateTimeBetween($createdAt, 'now'));
            $inventory->setCreatedAt($createdAt);
            $manager->persist($inventory);
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

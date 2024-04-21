<?php

namespace App\DataFixtures\AppFixtures;

use App\DataFixtures\AppFixtures\CoreFixtures;
use App\Entity\Room;
use Doctrine\Persistence\ObjectManager;

class InventoryFixtures extends CoreFixtures 
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

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures\AppFixtures;

use App\DataFixtures\AppFixtures\CoreFixtures;
use App\Entity\Date;
use App\Entity\Email;
use App\Entity\File;
use App\Entity\Notification;
use App\Entity\Picture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class MediaFixtures extends CoreFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //! date

        $dates = [];

        for ($i = 0; $i < 12; $i++) {
            $date = new Date();
            $date->
                setYear(floor(rand(1978,2024)))
                ->setMonth(floor(rand(1,12)))
                ->setDay(floor(rand(1,28)))
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $dates[] = $date;
            $manager->persist($date);
            $this->addReference("date_" . $i, $date);
        }


        //! Picture


        for ($i = 0; $i < 100; $i++) {
            $picture = new Picture();
            $picture
                ->setName($this->faker->word())
                ->setPath($this->faker->unique()->imageUrl())
                ->setMime($this->faker->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $manager->persist($picture);
            $this->addReference("picture_" . $i, $picture);
        }

        //! File

        $files = [];
        for ($i = 0; $i < 50; $i++) {
            $file = new File();
            $file
                ->setName($this->faker->unique()->word())
                ->setDocType($this->faker->word())
                ->setPath($this->faker->unique()->imageUrl())
                ->setMime($this->faker->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $files[] = $file;
            $this->addReference("file_" . $i, $file);
            $manager->persist($file);
        }
        // We fetch the pictures from the references to link them with the products

        
        //! Notification

        // We fetch the groups from the references to link them with the notifications.

        $groups = [];
        $i = 0;
        while ($this->hasReference("group_" . $i)) {
            $group = $this->getReference("group_" . $i);
            $groups[] = $group;
            $i++;
        }

        for ($i = 0; $i < 50; $i++) {
            $notification = new Notification();
            $notification
                ->setName($this->faker->unique()->word())
                ->setSlug($this->faker->unique()->slug(3, false))
                ->setContent($this->faker->text(1000))
                ->setType($this->faker->word())
                ->setComment($this->faker->text(1000))
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);


            // A notification can be sent to multiple groups , but each group must be unique.
            $nbGroups = rand(0, count($groups));
            $nbMaxGroups = rand(1, $nbGroups);
            $notificationGroups = [];
            if (!empty($groups)) {
                for ($j = 0; $j < $nbMaxGroups; ) {
                    $randomIndex = array_rand($groups);
                    $notificationGroup = $groups[$randomIndex];

                    if (!in_array($notificationGroup, $notificationGroups)) {
                        $notification->addGroupUser($notificationGroup);
                        $notificationGroups[] = $notificationGroup;
                        $j++;
                    }
                }
            }

            $manager->persist($notification);
        }


        $manager->flush();

    }
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
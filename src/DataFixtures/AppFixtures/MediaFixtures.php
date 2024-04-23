<?php

namespace App\DataFixtures\AppFixtures;

use App\DataFixtures\AppFixtures\CoreFixtures;
use App\Entity\Date;
use App\Entity\Email;
use App\Entity\File;
use App\Entity\Mime;
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
                setYear($this->faker->year())
                ->setMonth($this->faker->monthName())
                ->setDay($this->faker->dayOfMonth())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $dates[] = $date;
            $manager->persist($date);
            $this->addReference("date_" . $i, $date);
        }

        //! Mime

        $mimes = [];
        for ($i = 0; $i < 12; $i++) {
            $mime = new Mime();
            $mime
                ->setName($this->faker->unique()->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $mimes[] = $mime;
            $manager->persist($mime);

        }

        //! Picture


        for ($i = 0; $i < 300; $i++) {
            $picture = new Picture();
            $picture
                ->setName($this->faker->word())
                ->setSlug($this->faker->unique()->slug(3, false))
                ->setPath($this->faker->unique()->imageUrl())
                ->setMime($mimes[array_rand($mimes)])
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $manager->persist($picture);
            $this->addReference("picture_" . $i, $picture);
        }

        //! File

        $files = [];
        for ($i = 0; $i < 100; $i++) {
            $file = new File();
            $file
                ->setName($this->faker->unique()->word())
                ->setDocType($this->faker->word())
                ->setPath($this->faker->unique()->imageUrl())
                ->setMime($mimes[array_rand($mimes)])
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $files[] = $file;
            $manager->persist($file);
        }

        //! Email

        // we fetch the users from the references to be able to associate them with the emails
        $users = [];
        $i = 0;
        while ($this->hasReference("user_" . $i)) {
            $user = $this->getReference("user_" . $i);
            $users[] = $user;
            $i++;
        }

        for ($i = 0; $i < 40; $i++) {
            $email = new Email();
            $email
                ->setObject($this->faker->sentence())
                ->setContent($this->faker->text(1000))
                ->setStatus($this->faker->word())
                ->setSender($users[array_rand($users)])
                ->setDate($dates[array_rand($dates)])
                ->setDelivered($this->faker->boolean())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            // An email can have multiple files, but each file must be unique.
            $nbFiles = count($files) > 5 ? 5 : count($files);
            $nbMaxFiles = rand(0, $nbFiles);

            $emailFiles = [];
            if (!empty($files)) {
                for ($j = 0; $j < $nbMaxFiles; ) {
                    $randomIndex = array_rand($files);
                    $selectedFiles = $files[$randomIndex];

                    if (!in_array($selectedFiles, $emailFiles)) {
                        $email->addFile($selectedFiles);
                        $emailFiles[] = $selectedFiles;
                        $j++;
                    }
                }
            }

            // An email can have multiple receivers, but each receiver "alias user" must be unique.
            $nbReceivers = count($users) > 10 ? 10 : count($users);
            $nbMaxReceivers = rand(1, $nbReceivers);
            $emailReceivers = [];

            if (!empty($users)) {
                for ($j = 0; $j < $nbMaxReceivers; ) {
                    $randomIndex = array_rand($users);
                    $selectedReceivers = $users[$randomIndex];

                    if (!in_array($selectedReceivers, $emailReceivers)) {
                        $email->addReceiver($selectedReceivers);
                        $emailReceivers[] = $selectedReceivers;
                        $j++;
                    }
                }
            }
            $manager->persist($email);
        }

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
                ->setName($this->faker->unique()->word(1))
                ->setSlug($this->faker->unique()->slug(3, false))
                ->setContent($this->faker->text(1000))
                ->setType($this->faker->word(1))
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
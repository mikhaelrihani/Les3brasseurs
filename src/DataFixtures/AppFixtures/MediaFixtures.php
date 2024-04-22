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
            $date->setYear($this->faker->year());
            $date->setMonth($this->faker->monthName());
            $date->setDay($this->faker->dayOfMonth());
            $date->setCreatedAt(new \DateTime($this->faker->date()));
            $date->setUpdatedAt(new \DateTime($this->faker->date()));

            $dates[] = $date;
            $manager->persist($date);
            $this->addReference("date_" . $i, $date);
        }

        //! Mime

        $mimes = [];
        for ($i = 0; $i < 12; $i++) {
            $mime = new Mime();
            $mime->setName($this->faker->word());
            $mime->setCreatedAt(new \DateTime($this->faker->date()));
            $mime->setUpdatedAt(new \DateTime($this->faker->date()));

            $mimes[] = $mime;
            $manager->persist($mime);

        }

        //! Picture

        
        for ($i = 0; $i < 300; $i++) {
            $picture = new Picture();
            $picture->setName($this->faker->word());
            $picture->setSlug($this->faker->text(10));
            $picture->setPath($this->faker->imageUrl());
            $picture->setMime($mimes[array_rand($mimes)]);
            $picture->setCreatedAt(new \DateTime($this->faker->date()));
            $picture->setUpdatedAt(new \DateTime($this->faker->date()));

            $manager->persist($picture);
            $this->addReference("picture_" . $i, $picture);
        }

        //! File

        $files = [];
        for ($i = 0; $i < 100; $i++) {
            $file = new File();
            $file->setName($this->faker->word());
            $file->setDocType($this->faker->word());
            $file->setPath($this->faker->imageUrl());
            $file->setMime($mimes[array_rand($mimes)]);
            $file->setCreatedAt(new \DateTime($this->faker->date()));
            $file->setUpdatedAt(new \DateTime($this->faker->date()));

            $files[] = $file;
            $manager->persist($file);
        }

        //! Email

        // we retrieve the users from the references to be able to associate them with the emails

        $users = [];
        $i = 0;

        while ($this->hasReference("user_" . $i)) {
            $user = $this->getReference("user_" . $i);
            $users[] = $user;
            $i++;
        }

        for ($i = 0; $i < 12; $i++) {
            $email = new Email();
            $email->setObject($this->faker->sentence());
            $email->setContent($this->faker->text(1000));
            $email->setStatus($this->faker->word());
            $email->setSender($users[array_rand($users)]);
            $email->setDate($dates[array_rand($dates)]);
            $email->setDelivered($this->faker->boolean());
            $email->setCreatedAt(new \DateTime($this->faker->date()));
            $email->setUpdatedAt(new \DateTime($this->faker->date()));

            // An email can have multiple files, but each file must be unique.
            $nbFiles = rand(0, 4);
            $emailFiles = [];
            if (!empty($files)) {
                for ($j = 0; $j < $nbFiles; $j++) {
                    $randomIndex = array_rand($files);
                    $selectedFiles = $files[$randomIndex];

                    if (!in_array($selectedFiles, $emailFiles)) {
                        $email->addFile($selectedFiles);
                        $emailFiles[] = $selectedFiles;
                    }
                }
            }

            // An email can have multiple receivers, but each receiver "alias user" must be unique.
            $nbReceivers = rand(0, 10);
            $emailReceivers = [];
            if (!empty($users)) {
                for ($j = 0; $j < $nbReceivers; $j++) {
                    $randomIndex = array_rand($users);
                    $selectedReceivers = $users[$randomIndex];

                    if (!in_array($selectedReceivers, $emailReceivers)) {
                        $email->addReceiver($selectedReceivers);
                        $emailReceivers[] = $selectedReceivers;
                    }
                }
            }
            $manager->persist($email);
        }

        //! Notification

        // we retrieve the groups from the references to be able to associate them with the notifications

        $groups = [];
        $i = 0;

        while ($this->hasReference("group_" . $i)) {
            $group = $this->getReference("group_" . $i);
            $groups[] = $group;
            $i++;
        }

        for ($i = 0; $i < 50; $i++) {
            $notification = new Notification();
            $notification->setName($this->faker->word(1));
            $notification->setSlug($this->faker->word(1));
            $notification->setContent($this->faker->text(1000));
            $notification->setType($this->faker->word(1));
            $notification->setComment($this->faker->text(1000));
            $notification->setCreatedAt(new \DateTime($this->faker->date()));
            $notification->setUpdatedAt(new \DateTime($this->faker->date()));

            
            // A notification can be sent to multiple groups , but each group must be unique.
            $nbGroups = rand(0, 10);
            $notificationGroups = [];
            if (!empty($groups)) {
                for ($j = 0; $j < $nbGroups; $j++) {
                    $randomIndex = array_rand($groups);
                    $notificationGroup = $groups[$randomIndex];

                    if (!in_array($notificationGroup, $notificationGroups)) {
                        $notification->addGroupUser($notificationGroup);
                        $notificationGroups[] = $notificationGroup;
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
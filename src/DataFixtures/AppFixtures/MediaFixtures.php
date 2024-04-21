<?php

namespace App\DataFixtures\AppFixtures;

use App\DataFixtures\AppFixtures\CoreFixtures;
use App\Entity\Date;
use App\Entity\File;
use App\Entity\Mime;
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

        $pictures = [];
        for ($i = 0; $i < 300; $i++) {
            $picture = new Picture();
            $picture->setName($this->faker->word());
            $picture->setSlug($this->faker->text(10));
            $picture->setPath($this->faker->imageUrl());
            $picture->setMime($mimes[array_rand($mimes)]);
            $picture->setCreatedAt(new \DateTime($this->faker->date()));
            $picture->setUpdatedAt(new \DateTime($this->faker->date()));

            $pictures[] = $picture;
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
            $this->addReference("file_" . $i, $file);
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
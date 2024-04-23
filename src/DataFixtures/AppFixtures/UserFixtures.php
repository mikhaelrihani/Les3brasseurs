<?php

namespace App\DataFixtures\AppFixtures;

use App\DataFixtures\Provider\AppProvider;
use App\DataFixtures\AppFixtures\CoreFixtures;
use App\Entity\Group;
use App\Entity\Job;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\UserInfos;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends CoreFixtures
{

    public function load(ObjectManager $manager): void
    {
        $this->faker->addProvider(new AppProvider());

        //! User 
        $users = [];

        for ($i = 0; $i < 22; $i++) {
            $user = new User();
            $user
                ->setUuid(Uuid::v4())
                ->setRoles($this->faker->role())
                ->setPassword($this->userPasswordHasher->hashPassword($user, $this->faker->password(8, 20)))
                ->setFirstname($this->faker->firstName())
                ->setSurname($this->faker->lastName())
                ->setSlug($this->faker->unique()->slug(3, false))
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $users[] = $user;
            $manager->persist($user);
            $this->addReference("user_" . $i, $user);

            //! UserInfos

            $userInfos = new UserInfos();
            $userInfos
                ->setUser($user)
                ->setBusiness($this->faker->business())
                ->setphone($this->faker->unique()->phoneNumber())
                ->setWhatsApp($this->faker->unique()->phoneNumber())
                ->setAvatar($this->faker->imageUrl(640, 480, 'people', true))
                ->setEmail($this->faker->unique()->email())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $manager->persist($userInfos);
        }

        //! Groups

        for ($i = 0; $i < 12; $i++) {
            $group = new Group();
            $group
                ->setName($this->faker->unique()->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $manager->persist($group);
            $this->addReference("group_" . $i, $group);
        }

        //! Job

        for ($i = 0; $i < 12; $i++) {
            $job = new Job();
            $job
                ->setName($this->faker->unique()->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);
            $manager->persist($job);
        }

        $manager->flush();

    }
}
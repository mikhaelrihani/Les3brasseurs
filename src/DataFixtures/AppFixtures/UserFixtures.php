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
            $user->setUuid(Uuid::v4());
            $user->setRoles($this->faker->role());
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $this->faker->password(8, 20)));
            $user->setFirstname($this->faker->firstName());
            $user->setSurname($this->faker->lastName());
            $user->setSlug($this->faker->unique()->slug(3, false));
            $createdAt = $this->faker->dateTimeBetween('-5 years', 'now');
            $user->setUpdatedAt($this->faker->dateTimeBetween($createdAt, 'now'));
            $user->setCreatedAt($createdAt);

            $users[] = $user;
            $manager->persist($user);
            $this->addReference("user_" . $i, $user);

        //! UserInfos
        
            $userInfos = new UserInfos();
            $userInfos->setUser($user);
            $userInfos->setBusiness($this->faker->business());
            $userInfos->setphone($this->faker->unique()->phoneNumber());
            $userInfos->setWhatsApp($this->faker->unique()->phoneNumber());
            $userInfos->setAvatar($this->faker->imageUrl(640, 480, 'people', true));
            $userInfos->setEmail($this->faker->unique()->email());
            $createdAt = $this->faker->dateTimeBetween('-5 years', 'now');
            $userInfos->setUpdatedAt($this->faker->dateTimeBetween($createdAt, 'now'));
            $userInfos->setCreatedAt($createdAt);

            $manager->persist($userInfos);
        }

        //! Groups

        for ($i = 0; $i < 12; $i++) {
            $group = new Group();
            $group->setName($this->faker->unique()->word());
            $createdAt = $this->faker->dateTimeBetween('-5 years', 'now');
            $group->setUpdatedAt($this->faker->dateTimeBetween($createdAt, 'now'));
            $group->setCreatedAt($createdAt);

            $manager->persist($group);
            $this->addReference("group_" . $i, $group);
        }

        //! Job

        for ($i = 0; $i < 12; $i++) {
            $job = new Job();
            $job->setName($this->faker->unique()->word());
            $createdAt = $this->faker->dateTimeBetween('-5 years', 'now');
            $job->setUpdatedAt($this->faker->dateTimeBetween($createdAt, 'now'));
            $job->setCreatedAt($createdAt);
            $manager->persist($job);
        }

        $manager->flush();

    }
}
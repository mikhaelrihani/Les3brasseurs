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

    public const UserCount = 22;
    public function load(ObjectManager $manager): void
    {
        $this->faker->addProvider(new AppProvider());

        //! User 
        $users = [];

        for ($i = 0; $i < self::UserCount; $i++) {
            $user = new User();
            $user->setUuid(Uuid::v4());
            $user->setRoles($this->faker->role());
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $this->faker->password(8, 20)));
            $user->setFirstname($this->faker->firstName());
            $user->setSurname($this->faker->lastName());
            $user->setSlug($this->faker->slug(3, false));
            $user->setCreatedAt(new \DateTime($this->faker->date()));
            $user->setUpdatedAt(new \DateTime($this->faker->date()));

            $users[] = $user;
            $manager->persist($user);
            $this->addReference("user_" . $i, $user);

        }



        //! UserInfos

        foreach ($users as $user) {
            $userInfos = new UserInfos();
            $userInfos->setUser($user);
            $userInfos->setBusiness($this->faker->business());
            $userInfos->setphone($this->faker->phoneNumber());
            $userInfos->setWhatsApp($this->faker->phoneNumber());
            $userInfos->setAvatar($this->faker->imageUrl(640, 480, 'people', true));
            $userInfos->setEmail($this->faker->email());
            $userInfos->setCreatedAt(new \DateTime($this->faker->date()));
            $userInfos->setUpdatedAt(new \DateTime($this->faker->date()));

            $manager->persist($userInfos);
        }



        //! Groups

        $groups = [];
        for ($i = 0; $i < 12; $i++) {
            $group = new Group();
            $group->setName($this->faker->word());
            $group->setCreatedAt(new \DateTime($this->faker->date()));
            $group->setUpdatedAt(new \DateTime($this->faker->date()));

            $groups[] = $group;
            $manager->persist($group);
            $this->addReference("group_" . $i, $group);
        }

        //! Job

        $jobs = [];

        for ($i = 0; $i < 12; $i++) {
            $job = new Job();
            $job->setName($this->faker->word());
            $job->setCreatedAt(new \DateTime($this->faker->date()));
            $job->setUpdatedAt(new \DateTime($this->faker->date()));

            $jobs[] = $job;
            $manager->persist($job);
            $this->addReference("job_" . $i, $job);
        }

        $this->addReference(self::UserCount, $user);
        $manager->flush();

    }
}
<?php

namespace App\DataFixtures\AppFixtures;

use App\DataFixtures\Provider\AppProvider;
use App\DataFixtures\AppFixtures\CoreFixtures;
use App\Entity\Group;
use App\Entity\Receiver;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\UserInfos;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends CoreFixtures
{

    public function load(ObjectManager $manager): void
    {
        $this->faker->addProvider(new AppProvider());

        //! Groups

        $groups = [];
        for ($i = 0; $i < 12; $i++) {
            $group = new Group();
            $group
                ->setName($this->faker->unique()->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $manager->persist($group);
            $groups[] = $group;
            $this->addReference("group_" . $i, $group);
        }

        //! User 

        for ($k = 0; $k < 22; $k++) {
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

            $manager->persist($user);
            $this->addReference("user_" . $k, $user);

            //! UserInfos

            $userInfos = new UserInfos();
            $userInfos
                ->setUser($user)
                ->setBusiness($this->faker->business())
                ->setphone($this->faker->unique()->phoneNumber())
                ->setWhatsApp($this->faker->unique()->phoneNumber())
                ->setAvatar($this->faker->imageUrl(640, 480, 'people', true))
                ->setEmail($this->faker->unique()->email())
                ->setJob($this->faker->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);


            
            $userGroups = [];
            for ($j = 0; $j < rand(0, 3); ) {
                $randomIndex = rand(0, count($groups) - 1);
                $selectedGroup = $groups[$randomIndex];
                if (!in_array($selectedGroup, $userGroups)) {
                    $userGroups[] = $selectedGroup;
                    $userInfos->addGroupList($selectedGroup);
                    $j++;
                }

            }


            $manager->persist($userInfos);
            $this->addReference("userInfos_" . $k, $userInfos);
        }


        $manager->flush();

    }
}
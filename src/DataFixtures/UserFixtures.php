<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\AppProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use App\Entity\UserInfos;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends Fixture
{

    private $userPasswordHasher;
    private $faker;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->faker = Factory::create("fr_FR");
    }
    public function load(ObjectManager $manager): void
    {

        $this->faker->addProvider(new AppProvider());

        //! User 
        $users = [];
        for ($i = 0; $i < 7; $i++) {
            $user = new User();
            $user->setUuid(Uuid::v7());
            $user->setRoles($this->faker->role());
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $this->faker->password(8, 20)));
            $user->setFirstname($this->faker->firstName());
            $user->setSurname($this->faker->lastName());
            $user->setSlug($this->faker->slug(3, false));
            $user->setCreatedAt(new \DateTime($this->faker->date()));
            $user->setUpdatedAt(new \DateTime($this->faker->date()));

            $users[] = $user;
            $manager->persist($user);

        }

        //! UserInfos
        foreach ($users as $user){
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

        $manager->flush();
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    private const CREATE_NB = 25;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        $users = $this->userRepository->findAllNotAdmin();

        for ($t = 0; $t < self::CREATE_NB; $t++) {
            $task = new Task();
            $task->setTitle($faker->text(25));
            $task->setContent($faker->text(150));
            $task->setIsDone((bool)rand(0, 1));
            shuffle($users);
            $user = $users[0];
            /**
             * @var User $user
             */
            $task->setAuthor($user);
            $manager->persist($task);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}

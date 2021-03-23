<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    protected $encoder;

    public function __construct(
        UserPasswordEncoderInterface $encoder
    ) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin')
            ->setPassword($this->encoder->encodePassword($user, 'password'))
            ->setEmail('admin@mail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setIsVerified(true);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('user1')
            ->setPassword($this->encoder->encodePassword($user, 'password'))
            ->setEmail('user1@mail.com')
            ->setIsVerified(true);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('user2')
            ->setPassword($this->encoder->encodePassword($user, 'password'))
            ->setEmail('user2@mail.com')
            ->setIsVerified(false);
        $manager->persist($user);

        $manager->flush();
    }
}

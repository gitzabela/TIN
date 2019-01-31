<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setName('Izabela');
        $user->setEmail('izabela.konca@pjwstk.edu.pl');
        $password = $this->passwordEncoder->encodePassword($user, '1q2w3e4r');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setConfirmationToken(Uuid::uuid4()->toString());

        $manager->persist($user);
        $manager->flush();
    }
}

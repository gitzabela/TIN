<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Role;
use App\Entity\Spot;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EventFixtures extends Fixture
{
    private const ROLES_TO_ADD = [
        'Captain',
        'Driver',
        'Cook',
        'Hunter'
    ];

    private const EVENTS_TO_ADD = [
        [
            'name' => 'Night sailing in Mazury',
            'description' => 'Sit est beatae adipisci. Deleniti et illum excepturi dolor. Et omnis nobis adipisci nulla est sapiente non nesciunt. Ducimus cumque commodi nostrum debitis reprehenderit magni ipsa. Reprehenderit dolor ut dolore ducimus voluptatem.',
            'dateFrom' => '14.01.2019',
            'dateTo' => '31.01.2019',
            'location' => 'Mazury, Poland',
            'spots' => [
                ['role' => 'Driver'],
                ['role' => 'Captain'],
            ]
        ],
        [
            'name' => 'Weekend hunt in the forest of Białowieża',
            'description' => 'Sit est beatae adipisci. Deleniti et illum excepturi dolor. Et omnis nobis adipisci nulla est sapiente non nesciunt. Ducimus cumque commodi nostrum debitis reprehenderit magni ipsa. Reprehenderit dolor ut dolore ducimus voluptatem.',
            'dateFrom' => '01.02.2019',
            'dateTo' => '04.02.2019',
            'location' => 'Białowieża, Poland',
            'spots' => [
                ['role' => 'Driver'],
                ['role' => 'Cook'],
                ['role' => 'Hunter'],
                ['role' => 'Hunter'],
            ]
        ],
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $userIzabela = $this->createUser($manager);

        $roles = [];
        foreach (self::ROLES_TO_ADD as $roleName) {
            $role = new Role();
            $role->setName($roleName);
            $manager->persist($role);

            $roles[$roleName] = $role;
        }

        foreach (self::EVENTS_TO_ADD as $eventToAdd) {
            $event = new Event();
            $event->setOwner($userIzabela);
            $event->setTitle($eventToAdd['name']);
            $event->setDescription($eventToAdd['description']);
            $event->setDateFrom(new \DateTime($eventToAdd['dateFrom']));
            $event->setDateTo(new \DateTime($eventToAdd['dateTo']));
            $event->setLocation($eventToAdd['location']);
            foreach ($eventToAdd['spots'] as $spotToAdd) {
                $spot = new Spot();
                $spot->setEvent($event);
                $spot->setRole($roles[$spotToAdd['role']]);
                $manager->persist($spot);

                $event->addSpot($spot);
            }
            $manager->persist($event);
        }

        $manager->flush();
    }

    private function createUser(ObjectManager $manager): User
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

        return $user;
    }
}

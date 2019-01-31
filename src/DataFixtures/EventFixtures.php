<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Role;
use App\Entity\Spot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

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

    public function load(ObjectManager $manager)
    {
        $roles = [];
        foreach (self::ROLES_TO_ADD as $roleName) {
            $role = new Role();
            $role->setName($roleName);
            $manager->persist($role);

            $roles[$roleName] = $role;
        }

        foreach (self::EVENTS_TO_ADD as $eventToAdd) {
            $event = new Event();
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
}

<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    private function save(User $user): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    public function register(User $user): void
    {
        $user->setRoles(['ROLE_USER_NOT_CONFIRMED']);
        $user->setConfirmationToken(Uuid::uuid4()->toString());
        $this->save($user);
    }

    /**
     * @param string $confirmationToken
     *
     * @throws UserDoesNotExist
     * @throws UserEmailIsAlreadyConfirmed
     */
    public function confirmUserEmail(string $confirmationToken): void
    {
        $user = $this->findOneBy(['confirmationToken' => $confirmationToken]);
        if (!$user) {
            throw new UserDoesNotExist("There's no user for this token");
        }

        if ($user->isConfirmed()) {
            throw new UserEmailIsAlreadyConfirmed('Your email is already confirmed.');
        }

        $user->confirm();

        $this->save($user);
    }
}

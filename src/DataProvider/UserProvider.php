<?php


namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserProvider
 * @package App\DataProvider
 *
 * @author Corentin Boutillier <corentinboutillier@gmail.com>
 */
final class UserProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    static protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return User::class === $resourceClass;
    }

    /**
     * @param string $resourceClass
     * @param $id
     * @param string|null $operationName
     * @param array $context
     * @return User|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?User
    {
        return self::$entityManager->getRepository(User::class)
            ->findOneByUuidOrEmailConfirmationUuidOrResetPasswordUuid($id);
    }
}
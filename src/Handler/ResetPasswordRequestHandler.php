<?php


namespace App\Handler;

use App\Entity\Client;
use App\Entity\ResetPasswordRequest;
use App\Service\UserMailerService;
use App\Service\UuidHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class ResetPasswordRequestHandler
 * @package App\Handler
 *
 * @author Corentin Boutillier <corentinboutillier@gmail.com>
 */
final class ResetPasswordRequestHandler implements MessageHandlerInterface
{

    /**
     * @var EntityManagerInterface
     */
    static protected $entityManager;

    /**
     * @var UuidHelper
     */
    static protected $uuidHelper;

    /**
     * @var UserMailerService
     */
    static protected $clientMailerService;

    public function __construct(EntityManagerInterface $entityManager,
                                UuidHelper $uuidHelper,
                                UserMailerService $clientMailerService)
    {
        self::$entityManager = $entityManager;
        self::$uuidHelper = $uuidHelper;
        self::$clientMailerService = $clientMailerService;
    }

    /**
     * @param ResetPasswordRequest $forgotPassword
     * @throws TransportExceptionInterface
     */
    public function __invoke(ResetPasswordRequest $forgotPassword)
    {
        $client = self::$entityManager->getRepository(Client::class)
            ->findOneByEmail($forgotPassword->email);
        if (!is_null($client)) {
            $client->setResetPasswordUuid(self::$uuidHelper->uuidGeneration());

            self::$entityManager->flush();
            self::$clientMailerService->sendResetPasswordEmail($client);
        }
    }
}
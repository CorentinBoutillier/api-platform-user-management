<?php


namespace App\Controller;

use App\Entity\User;
use App\Service\UserMailerService;
use App\Service\UuidHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserRegister
 * @package App\Controller
 *
 * @author Corentin Boutillier <corentinboutillier@gmail.com>
 */
class UserRegister
{
    /**
     * @var EntityManagerInterface
     */
    static protected $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    static protected $encoder;

    /**
     * @var UuidHelper
     */
    static protected $uuidHelper;

    /**
     * @var UserMailerService
     */
    static protected $clientMailerService;

    /**
     * UserRegister constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     * @param UuidHelper $uuidHelper
     * @param UserMailerService $clientMailerService
     */
    public function __construct(EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $encoder,
                                UuidHelper $uuidHelper,
                                UserMailerService $clientMailerService)
    {
        self::$entityManager = $entityManager;
        self::$encoder = $encoder;
        self::$uuidHelper = $uuidHelper;
        self::$clientMailerService = $clientMailerService;
    }

    /**
     * @param User $data
     * @return User
     * @throws TransportExceptionInterface
     */
    public function __invoke(User $data): User
    {
        if (!is_null(self::$entityManager->getRepository(User::class)->findOneByEmail($data->getEmail()))) {
            throw new BadRequestHttpException('Email already used');
        }

        $data->setPassword(self::$encoder->encodePassword($data, $data->getPassword()))
            ->setUuid(self::$uuidHelper->uuidGeneration())
            ->setEmailConfirmationUuid(self::$uuidHelper->uuidGeneration());

        self::$clientMailerService->sendAddrressValidationEmail($data);
        return $data;
    }
}
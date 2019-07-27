<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserValidEmail
 * @package App\Controller
 *
 * @author Corentin Boutillier <corentinboutillier@gmail.com>
 */
class UserValidEmail
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
     * UserValidEmail constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $encoder)
    {
        self::$entityManager = $entityManager;
        self::$encoder = $encoder;
    }

    /**
     * @param User $data
     * @return User
     */
    public function __invoke(User $data): User
    {
        $data->setValidatedEmail(true)
            ->setEmailConfirmationUuid(null);
        self::$entityManager->flush();
        return $data;
    }
}
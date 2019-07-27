<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserChangePassword
 * @package App\Controller
 *
 * @author Corentin Boutillier <corentinboutillier@gmail.com>
 */
class UserChangePassword
{
    /**
     * @var UserPasswordEncoderInterface
     */
    static protected $encoder;

    /**
     * UserChangePassword constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        self::$encoder = $encoder;
    }

    /**
     * @param User $data
     * @return User
     */
    public function __invoke(User $data): User
    {
        return $data->setPassword(self::$encoder->encodePassword($data, $data->getPassword()))
            ->setResetPasswordUuid(null);
    }
}
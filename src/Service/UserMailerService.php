<?php


namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\NamedAddress;

/**
 * Class UserMailerService
 * @package App\Service
 *
 * @author Corentin Boutillier <corentinboutillier@gmail.com>
 */
class UserMailerService
{
    /**
     * @var ContainerInterface
     */
    static protected $container;

    /**
     * @var MailerInterface
     */
    static protected $mailer;

    /**
     * @var string
     */
    static protected $sender;

    /**
     * @var string
     */
    static protected $frontUrl;

    /**
     * @var string
     */
    static protected $senderName;

    public function __construct(ContainerInterface $container,
                                MailerInterface $mailer)
    {
        self::$container = $container;
        self::$mailer = $mailer;
        self::$sender = $container->getParameter('MAIL_SENDER');
        self::$frontUrl = $container->getParameter('FRONT_URL');
        self::$senderName = $container->getParameter('SENDER_NAME');
    }

    /**
     * @param User $user
     * @throws TransportExceptionInterface
     */
    public function sendAddrressValidationEmail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new NamedAddress(self::$sender, self::$senderName))
            ->to(new NamedAddress($user->getEmail(), $user->getFirstname()))
            ->subject('Please confirm your mail address !')
            ->htmlTemplate('emails/user/signup.html.twig')
            ->context([
                'fontUrl' => self::$frontUrl,
                'user' => $user,
            ])
        ;
        self::$mailer->send($email);
    }

    /**
     * @param User $user
     * @throws TransportExceptionInterface
     */
    public function sendResetPasswordEmail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new NamedAddress(self::$sender, self::$senderName))
            ->to(new NamedAddress($user->getEmail(), $user->getFirstname()))
            ->subject('Reset your password')
            ->htmlTemplate('emails/user/reset-password.html.twig')
            ->context([
                'fontUrl' => self::$frontUrl,
                'user' => $user,
            ])
        ;
        self::$mailer->send($email);
    }
}
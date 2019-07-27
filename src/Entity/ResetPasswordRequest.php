<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ResetPasswordRequest
 * @package App\Entity
 *
 * @author Corentin Boutillier <corentinboutillier@gmail.com>
 *
 * @ApiResource(
 *      messenger=true,
 *      collectionOperations={
 *          "post"={
 *              "swagger_context"={
 *                  "tags"={
 *     			        "Password Management",
 *     		        },
 *                  "summary"="Reset password request",
 *              },
 *              "path"="/reset-password-requests",
 *              "status"=202,
 *          }
 *      },
 *      itemOperations={},
 *      output=false
 * )
 */
final class ResetPasswordRequest
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;
}
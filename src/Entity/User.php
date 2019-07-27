<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UserChangePassword;
use App\Controller\UserRegister;
use App\Controller\UserValidEmail;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ResetPasswordRequest
 * @package App\Entity
 *
 * @author Corentin Boutillier <corentinboutillier@gmail.com>
 *
 * @ApiResource(
 *     collectionOperations = {
 *          "get"={
 *              "path"="/users",
 *              "method"="GET",
 *              "access_control"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"user:view"}},
 *          },
 *          "register"={
 *              "swagger_context"={
 *                  "tags"={
 *     			        "Authentication",
 *     		        },
 *                  "summary"="User registration",
 *              },
 *              "method"="POST",
 *              "path"="/register",
 *              "controller"=UserRegister::class,
 *              "denormalization_context"={"groups"={"user:register"}},
 *              "normalization_context"={"groups"={"user:view"}},
 *          },
 *          "authentication"={
 *              "route_name"="authentication_token",
 *              "swagger_context"={
 *                  "tags"={
 *     			        "Authentication",
 *     		        },
 *                  "summary"="User authentication",
 *                  "parameters"={
 *                      {
 *                          "in"="body",
 *                          "schema"={
 *                              "type"="object",
 *                              "required"={
 *                                  "email",
 *                                  "password"
 *                              },
 *                              "properties"={
 *                                   "email"={
 *                                      "type"="string"
 *                                   },
 *                                   "password"={
 *                                      "type"="string"
 *                                   },
 *                              },
 *                          },
 *                      },
 *                  },
 *                  "responses"={
 *                      "200"={
 *                          "description"="Authentication token",
 *                          "schema"={
 *                              "type"="object",
 *                              "properties"={
 *                                   "token"={
 *                                      "type"="string"
 *                                   },
 *                              },
 *                          },
 *                      },
 *                      "400"={
 *                          "description"="Bad request",
 *                          "schema"={
 *                              "type"="object",
 *                              "properties"={
 *                                  "code"={
 *                                      "type"="integer",
 *                                  },
 *                                  "message"={
 *                                      "type"="string",
 *                                  },
 *                              },
 *                          },
 *                      },
 *                      "401" = {
 *                          "description"="Bad credentials",
 *                          "schema"={
 *                              "type"="object",
 *                              "properties"={
 *                                  "code"={
 *                                      "type"="integer",
 *                                  },
 *                                  "message"={
 *                                      "type"="string",
 *                                  },
 *                              },
 *                          },
 *                      }
 *                  },
 *                  "consumes"={
 *                      "application/json",
 *                  },
 *                  "produces"={
 *                      "application/json"
 *                  },
 *              },
 *          },
 *     },
 *     itemOperations={
 *          "get"={
 *              "swagger_context"={
 *                  "parameters"={
 *                      {
 *                          "in"="path",
 *                          "name"="id",
 *                          "type"="string",
 *                          "required"="true",
 *                          "description"="User uuid",
 *                      },
 *                  },
 *              },
 *              "path"="/users/{id}",
 *              "method"="GET",
 *              "access_control"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object == user)",
 *              "access_control_message"="Sorry, but you are not the owner.",
 *              "normalization_context"={"groups"={"user:view"}},
 *          },
 *          "put"={
 *              "swagger_context"={
 *                  "summary"="Update the User resource.",
 *                  "parameters"={
 *                      {
 *                          "in"="path",
 *                          "name"="id",
 *                          "type"="string",
 *                          "required"="true",
 *                          "description"="User uuid",
 *                      },
 *                  },
 *              },
 *              "path"="/users/{id}",
 *              "method"="PUT",
 *              "access_control"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object == user)",
 *              "access_control_message"="Sorry, but you are not the owner.",
 *              "denormalization_context"={"groups"={"user:update"}},
 *              "normalization_context"={"groups"={"user:view"}},
 *          },
 *          "valid_email"={
 *              "swagger_context"={
 *                  "tags"={
 *     			        "Authentication",
 *     		        },
 *                  "summary"="User email validation",
 *                  "parameters"={
 *                      {
 *                          "in"="path",
 *                          "name"="id",
 *                          "type"="string",
 *                          "required"="true",
 *                          "description"="Email confirmation uuid",
 *                      },
 *                  },
 *              },
 *              "path"="/valid-email/{id}",
 *              "method"="GET",
 *              "controller"=UserValidEmail::class,
 *              "normalization_context"={"groups"={"user:view"}},
 *              "status"=202
 *          },
 *          "change_password"={
 *              "swagger_context"={
 *                  "tags"={
 *     			        "Password Management",
 *     		        },
 *                  "summary"="User password modification",
 *                  "parameters"={
 *                      {
 *                          "in"="path",
 *                          "name"="id",
 *                          "type"="string",
 *                          "required"="true",
 *                          "description"="Reset password uuid",
 *                      },
 *                  },
 *              },
 *              "path"="/change-password/{id}",
 *              "method"="POST",
 *              "controller"=UserChangePassword::class,
 *              "denormalization_context"={"groups"={"user:change-password"}},
 *              "normalization_context"={"groups"={"user:view"}},
 *              "status"=202
 *          }
 *     }
 * )
 *
 * @ORM\Table(name="user_account")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="email", message="Email already used")
 */
class User implements UserInterface
{

    /**
     * @ApiProperty(identifier=false)
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"user:view"})
     * @ApiProperty(identifier=true)
     *
     * @ORM\Column(type="string", length=255)
     */
    private $uuid;

    /**
     * @Groups({"user:register", "user:view", "user:update"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @Groups({"user:register", "user:view", "user:update"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @Groups({"user:register", "user:login", "user:view", "user:update"})
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @Groups({"user:register", "user:login", "user:change-password"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validatedEmail = false;

    /**
     * @Groups({"user:view"})
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailConfirmationUuid = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetPasswordUuid = null;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @Groups({"user:view"})
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Groups({"user:view"})
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function setPrePersistValues()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setPreUpdateValues()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getValidatedEmail(): ?bool
    {
        return $this->validatedEmail;
    }

    public function setValidatedEmail(bool $validatedEmail): self
    {
        $this->validatedEmail = $validatedEmail;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getEmailConfirmationUuid(): ?string
    {
        return $this->emailConfirmationUuid;
    }

    public function setEmailConfirmationUuid(?string $emailConfirmationUuid): self
    {
        $this->emailConfirmationUuid = $emailConfirmationUuid;

        return $this;
    }

    public function getResetPasswordUuid(): ?string
    {
        return $this->resetPasswordUuid;
    }

    public function setResetPasswordUuid(?string $resetPasswordUuid): self
    {
        $this->resetPasswordUuid = $resetPasswordUuid;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function eraseCredentials() {}

    public function getSalt() {}
}

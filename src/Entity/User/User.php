<?php

declare(strict_types=1);

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Column(type="user_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @ORM\Embedded(class="Name")
     */
    private Name $name;

    /**
     * @ORM\Column(type="user_email")
     */
    private Email $email;

    /**
     * @ORM\Column(type="string")
     */
    private string $passwordHash;

    private function __construct(Id $id, Name $name, Email $email, string $passwordHash)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }


    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function changeEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function changePasswordHash($passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}

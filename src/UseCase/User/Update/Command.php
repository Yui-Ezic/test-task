<?php


namespace App\UseCase\User\Update;


class Command
{
    public string $id;
    public ?string $firstName;
    public ?string $lastName;
    public ?string $email;
    public ?string $password;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
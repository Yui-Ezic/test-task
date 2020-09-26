<?php


namespace App\UseCase\User\Delete;


class Command
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
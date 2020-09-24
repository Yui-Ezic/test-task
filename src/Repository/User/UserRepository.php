<?php


namespace App\Repository\User;


use App\Entity\User\Id;
use App\Entity\User\User;

interface UserRepository
{
    public function get(Id $id): User;

    public function add(User $user): void;

    public function remove(User $user): void;
}
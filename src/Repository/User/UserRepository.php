<?php


namespace App\Repository\User;


use App\Entity\User\Email;
use App\Entity\User\Id;
use App\Entity\User\User;
use App\Repository\EntityNotFoundException;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface UserRepository
{
    /**
     * @param Id $id
     * @return User
     * @throws EntityNotFoundException
     */
    public function get(Id $id): User;

    public function add(User $user): void;

    public function remove(User $user): void;

    /**
     * @return User[]
     */
    public function getAll(): array;

    public function hasByEmail(Email $email): bool;

    public function getAllPaginated(int $page, int $size): PaginationInterface;
}
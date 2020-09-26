<?php


namespace App\UseCase\User\Update;


use App\Entity\User\Email;
use App\Entity\User\Id;
use App\Entity\User\Name;
use App\Repository\User\UserRepository;
use App\Service\Flusher;
use App\Service\User\PasswordHasher;
use DomainException;

class Handler
{
    private UserRepository $repository;
    private PasswordHasher $passwordHasher;
    private Flusher $flusher;

    public function __construct(UserRepository $repository, PasswordHasher $passwordHasher, Flusher $flusher)
    {
        $this->repository = $repository;
        $this->passwordHasher = $passwordHasher;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->repository->get(new Id($command->id));

        if ($command->firstName || $command->lastName) {
            $user->changeName(new Name(
                $command->firstName ?: $user->getName()->getFirst(),
                $command->lastName ?: $user->getName()->getLast()
            ));
        }

        if ($command->email) {
            $email = new Email($command->email);
            if ($this->repository->hasByEmail($email)) {
                throw new DomainException('User with this email already exists.');
            }

            $user->changeEmail($email);
        }

        if ($command->password) {
            $user->changePasswordHash($this->passwordHasher->hash($command->password));
        }

        $this->flusher->flush();
    }
}
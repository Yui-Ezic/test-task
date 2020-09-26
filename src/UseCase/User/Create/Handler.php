<?php


namespace App\UseCase\User\Create;


use App\Entity\User\Email;
use App\Entity\User\Id;
use App\Entity\User\Name;
use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\Flusher;
use App\Service\User\PasswordHasher;
use DomainException;

class Handler
{
    private UserRepository $repository;
    private Flusher $flusher;
    private PasswordHasher $passwordHasher;

    public function __construct(UserRepository $repository, PasswordHasher $passwordHasher, Flusher $flusher)
    {
        $this->repository = $repository;
        $this->flusher = $flusher;
        $this->passwordHasher = $passwordHasher;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->repository->hasByEmail($email)) {
            throw new DomainException('User with this email already exists.');
        }

        $user = new User(
            Id::generate(),
            new Name($command->firstName, $command->lastName),
            $email,
            $this->passwordHasher->hash($command->password)
        );

        $this->repository->add($user);

        $this->flusher->flush();
    }
}
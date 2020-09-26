<?php


namespace App\UseCase\User\Delete;


use App\Entity\User\Id;
use App\Repository\User\UserRepository;
use App\Service\Flusher;

class Handler
{
    private UserRepository $repository;
    private Flusher $flusher;

    public function __construct(UserRepository $repository, Flusher $flusher)
    {
        $this->repository = $repository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->repository->get(new Id($command->id));

        $this->repository->remove($user);

        $this->flusher->flush();
    }
}
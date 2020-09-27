<?php


namespace App\Repository\User;


use App\Entity\User\Email;
use App\Entity\User\Id;
use App\Entity\User\User;
use App\Repository\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class DoctrineUserRepository implements UserRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repo;

    private PaginatorInterface $paginator;

    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
        $this->paginator = $paginator;
    }

    public function get(Id $id): User
    {
        /** @var User $user */
        if (!$user = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('User is not found.');
        }

        return $user;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }

    public function getAll(): array
    {
        return $this->repo->findAll();
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.email = :email')
            ->setParameter(':email', $email->getValue())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function getAllPaginated(int $page, int $size): PaginationInterface
    {
        return $this->paginator->paginate($this->repo->findAll(), $page, $size);
    }
}
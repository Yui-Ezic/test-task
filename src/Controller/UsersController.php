<?php


namespace App\Controller;

use App\Entity\User\Id;
use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\UseCase\User\Create;
use App\UseCase\User\Delete;
use App\UseCase\User\Update;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users", name="users.")
 * @package App\Controller
 */
class UsersController
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("", name="index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        $users = $this->repository->getAll();

        $response['items'] = array_map(function (User $user) {
            return $this->convertUserToArray($user);
        }, $users);

        return new JsonResponse($response);
    }

    /**
     * @Route("", name="create", methods={"POST"})
     * @param Request $request
     * @param Create\Handler $handler
     * @return Response
     * @throws JsonException
     */
    public function create(Request $request, Create\Handler $handler): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $firstName = $data['first_name'] ?? null;
        $lastName = $data['last_name'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            return new JsonResponse([
                'error' => [
                    'status' => 400,
                    'message' => 'One of required parameters is not set or empty.'
                ]
            ], 400);
        }

        $command = new Create\Command($firstName, $lastName, $email, $password);

        $handler->handle($command);

        return new JsonResponse(['success' => true], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param string $id
     * @return Response
     */
    public function show(string $id): Response
    {
        $user = $this->repository->get(new Id($id));

        return new JsonResponse($this->convertUserToArray($user));
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     * @param Request $request
     * @param string $id
     * @param Update\Handler $handler
     * @return Response
     * @throws JsonException
     */
    public function update(Request $request, string $id, Update\Handler $handler): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new Update\Command($id);
        $command->firstName = $data['first_name'] ?? null;
        $command->lastName = $data['last_name'] ?? null;
        $command->email = $data['email'] ?? null;
        $command->password = $data['password'] ?? null;

        $handler->handle($command);

        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param string $id
     * @param Delete\Handler $handler
     * @return Response
     */
    public function delete(string $id, Delete\Handler $handler): Response
    {
        $command = new Delete\Command($id);
        $handler->handle($command);

        return new JsonResponse(['success' => true]);
    }

    /**
     * @param User $user
     * @return array
     */
    private function convertUserToArray(User $user): array
    {
        return [
            'id' => $user->getId()->getValue(),
            'name' => [
                'first' => $user->getName()->getFirst(),
                'last' => $user->getName()->getLast(),
                'full' => $user->getName()->getFull()
            ],
            'email' => $user->getEmail()->getValue()
        ];
    }
}
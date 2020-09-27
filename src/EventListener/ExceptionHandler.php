<?php


namespace App\EventListener;

use App\Repository\EntityNotFoundException;
use DomainException;
use JsonException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

class ExceptionHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $request = $event->getRequest();

        if (in_array('application/json', $request->getAcceptableContentTypes(), true)) {
            $event->setResponse($this->createJsonResponse($throwable));
        }
    }

    public function createJsonResponse(Throwable $throwable): JsonResponse
    {
        if ($throwable instanceof JsonException) {
            return new JsonResponse([
                'error' => [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Invalid json'
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($throwable instanceof DomainException) {
            return new JsonResponse([
                'error' => [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $throwable->getMessage()
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($throwable instanceof EntityNotFoundException) {
            return new JsonResponse([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => $throwable->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }

        $status = $throwable instanceof HttpExceptionInterface ? $throwable->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        return new JsonResponse([
            'error' => [
                'status' => $status,
                'message' => 'Unknown error'
            ]
        ]);
    }
}
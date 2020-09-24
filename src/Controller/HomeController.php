<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
    /**
     * @Route ("/", name="home")
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(['message' => 'Welcome']);
    }
}
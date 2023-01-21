<?php

declare(strict_types=1);

namespace App\Weather\Infrastructure\Controller;

use App\Weather\Application\UseCase\Query\FetchCurrent\FetchCurrentWeatherHandler;
use App\Weather\Application\UseCase\Query\FetchCurrent\FetchCurrentWeatherQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/weather/current')]
class CurrentWeatherController extends AbstractController
{
    public function __invoke(Request $request, FetchCurrentWeatherHandler $handler): Response
    {
        $weather = $handler(new FetchCurrentWeatherQuery((string) $request->query->get('city')));

        if (!$weather) {
            return $this->json(['message' => 'Not Found!'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['currentWeather' => $weather]);
    }
}

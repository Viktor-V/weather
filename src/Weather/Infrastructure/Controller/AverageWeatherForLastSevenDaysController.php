<?php

declare(strict_types=1);

namespace App\Weather\Infrastructure\Controller;

use App\Weather\Application\UseCase\Query\FetchAverage\FetchAverageWeatherForLastSevenDaysHandler;
use App\Weather\Application\UseCase\Query\FetchAverage\FetchAverageWeatherForLastSevenDaysQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/weather/last-seven-days')]
class AverageWeatherForLastSevenDaysController extends AbstractController
{
    public function __invoke(Request $request, FetchAverageWeatherForLastSevenDaysHandler $handler): Response
    {
        $weather = $handler(new FetchAverageWeatherForLastSevenDaysQuery((string) $request->query->get('city')));

        if (!$weather) {
            return $this->json(['message' => 'Not Found!'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['averageWeather' => $weather]);
    }
}

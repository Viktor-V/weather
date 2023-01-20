<?php

declare(strict_types=1);

namespace App\Weather\Application\UseCase\Query\FetchCurrent;

use App\Weather\Domain\DataTransfer\CurrentWeatherDTO;
use App\Weather\Domain\Query\WeatherQueryInterface;

class FetchCurrentWeatherHandler
{
    public function __construct(
        private WeatherQueryInterface $query
    ) {
    }

    public function __invoke(FetchCurrentWeatherQuery $query): ?CurrentWeatherDTO
    {
        return $this->query->getCurrentWeather($query->city);
    }
}

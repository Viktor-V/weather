<?php

declare(strict_types=1);

namespace App\Weather\Application\UseCase\Query\FetchAverage;

use App\Weather\Domain\DataTransfer\AverageWeatherDTO;
use App\Weather\Domain\Query\WeatherQueryInterface;

class FetchAverageWeatherForLastSevenDaysHandler
{
    public function __construct(
        private WeatherQueryInterface $query
    ) {
    }

    public function __invoke(FetchAverageWeatherForLastSevenDaysQuery $query): ?AverageWeatherDTO
    {
        return $this->query->getAverageWeatherForLastSevenDays($query->city);
    }
}

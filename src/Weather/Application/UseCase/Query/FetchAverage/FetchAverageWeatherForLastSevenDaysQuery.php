<?php

declare(strict_types=1);

namespace App\Weather\Application\UseCase\Query\FetchAverage;

readonly class FetchAverageWeatherForLastSevenDaysQuery
{
    public function __construct(
        public string $city
    ) {
    }
}

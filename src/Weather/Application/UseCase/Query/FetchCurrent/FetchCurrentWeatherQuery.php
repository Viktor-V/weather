<?php

declare(strict_types=1);

namespace App\Weather\Application\UseCase\Query\FetchCurrent;

readonly class FetchCurrentWeatherQuery
{
    public function __construct(
        public string $city
    ) {
    }
}

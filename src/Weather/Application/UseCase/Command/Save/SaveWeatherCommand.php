<?php

declare(strict_types=1);

namespace App\Weather\Application\UseCase\Command\Save;

readonly class SaveWeatherCommand
{
    public function __construct(
        public string $date,
        public string $city,
        public float $temperature,
        public float $windSpeed
    ) {
    }
}

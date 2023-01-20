<?php

declare(strict_types=1);

namespace App\Weather\Domain\DataTransfer;

readonly class AverageWeatherDTO
{
    public function __construct(
        public string $city,
        public float $temperature,
        public float $windSpeed
    ) {
    }
}

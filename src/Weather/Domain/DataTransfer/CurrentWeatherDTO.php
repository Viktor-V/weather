<?php

declare(strict_types=1);

namespace App\Weather\Domain\DataTransfer;

readonly class CurrentWeatherDTO
{
    public function __construct(
        public string $date,
        public string $city,
        public float $temperature,
        public float $windSpeed
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Weather\Domain\Entity;

class Weather
{
    public function __construct(
        private string $date,
        private string $city,
        private float $temperature,
        private float $windSpeed
    ) {
    }

    public function update(float $temperature, float $windSpeed): void
    {
        $this->temperature = $temperature;
        $this->windSpeed = $windSpeed;
    }
}

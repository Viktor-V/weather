<?php

declare(strict_types=1);

namespace App\Weather\Domain\Query;

use App\Weather\Domain\DataTransfer\AverageWeatherDTO;
use App\Weather\Domain\DataTransfer\CurrentWeatherDTO;

interface WeatherQueryInterface
{
    public function getCurrentWeather(string $city): ?CurrentWeatherDTO;
    public function getAverageWeatherForLastSevenDays(string $city): ?AverageWeatherDTO;
}

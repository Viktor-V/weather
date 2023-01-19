<?php

declare(strict_types=1);

namespace App\Weather\Domain\Repository;

use App\Weather\Domain\Entity\Weather;

interface WeatherRepositoryInterface
{
    public function findByDateAndCity(string $date, string $city): ?Weather;
    public function save(Weather $weather): void;
}

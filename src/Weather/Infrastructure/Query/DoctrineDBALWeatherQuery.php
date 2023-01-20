<?php

declare(strict_types=1);

namespace App\Weather\Infrastructure\Query;

use App\Weather\Domain\DataTransfer\CurrentWeatherDTO;
use App\Weather\Domain\Query\WeatherQueryInterface;
use Doctrine\DBAL\Connection;
use DateTimeImmutable;

class DoctrineDBALWeatherQuery implements WeatherQueryInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function getCurrentWeather(string $city): ?CurrentWeatherDTO
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $weatherData = $queryBuilder
            ->select('*')
            ->from('weather')
            ->where('city = :city')
            ->andWhere('date = :date')
            ->setParameters([
                'city' => $city,
                'date' => (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s')
            ])
            ->fetchAssociative();

        if (!$weatherData) {
            return null;
        }

        return new CurrentWeatherDTO(
            $weatherData['date'],
            $weatherData['city'],
            (float) $weatherData['temperature'],
            (float) $weatherData['wind_speed'],
        );
    }
}

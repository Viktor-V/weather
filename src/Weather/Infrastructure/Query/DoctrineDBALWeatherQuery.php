<?php

declare(strict_types=1);

namespace App\Weather\Infrastructure\Query;

use App\Weather\Domain\DataTransfer\AverageWeatherDTO;
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

    public function getAverageWeatherForLastSevenDays(string $city): ?AverageWeatherDTO
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $currentDate = (new DateTimeImmutable('today midnight'));
        $sevenDaysPastDate = (new DateTimeImmutable('today midnight'))->modify('-7 days');

        $weathers = $queryBuilder
            ->select('*')
            ->from('weather')
            ->where('city = :city')
            ->andWhere('date > :startDate')
            ->andWhere('date <= :endDate')
            ->setParameters([
                'city' => $city,
                'startDate' => $sevenDaysPastDate->format('Y-m-d H:i:s'),
                'endDate' => $currentDate->format('Y-m-d H:i:s')
            ])
            ->fetchAllAssociative();

        if (empty($weathers)) {
            return null;
        }

        $temperature = null;
        $windSpeed = null;
        
        foreach ($weathers as $weatherData) {
            $temperature += $weatherData['temperature'];
            $windSpeed += $weatherData['wind_speed'];
        }

        return new AverageWeatherDTO(
            $city,
            round($temperature / count($weathers), 2),
            round($windSpeed / count($weathers), 2)
        );
    }
}

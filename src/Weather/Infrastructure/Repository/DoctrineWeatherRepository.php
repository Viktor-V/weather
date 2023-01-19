<?php

declare(strict_types=1);

namespace App\Weather\Infrastructure\Repository;

use App\Weather\Domain\Entity\Weather;
use App\Weather\Domain\Repository\WeatherRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineWeatherRepository implements WeatherRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function findByDateAndCity(string $date, string $city): ?Weather
    {
        $weather = $this->entityManager->getRepository(Weather::class)->findOneBy(['date' => $date, 'city' => $city]);

        if (!$weather instanceof Weather) {
            return null;
        }

        return $weather;
    }

    public function save(Weather $weather): void
    {
        $this->entityManager->persist($weather);
        $this->entityManager->flush();
    }
}

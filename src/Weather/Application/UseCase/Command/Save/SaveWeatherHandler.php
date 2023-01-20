<?php

declare(strict_types=1);

namespace App\Weather\Application\UseCase\Command\Save;

use App\Weather\Domain\Entity\Weather;
use App\Weather\Domain\Repository\WeatherRepositoryInterface;

class SaveWeatherHandler
{
    public function __construct(
        private WeatherRepositoryInterface $weatherRepository
    ) {
    }

    public function __invoke(SaveWeatherCommand $command): void
    {
        $weather = $this->weatherRepository->findByDateAndCity($command->date, $command->city);

        if ($weather) {
            $weather->update($command->temperature, $command->windSpeed);
        } else {
            $weather = new Weather(
                $command->date,
                $command->city,
                $command->temperature,
                $command->windSpeed,
            );
        }

        $this->weatherRepository->save($weather);
    }
}

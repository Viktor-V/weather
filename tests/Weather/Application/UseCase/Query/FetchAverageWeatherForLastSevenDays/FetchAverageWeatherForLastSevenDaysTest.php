<?php

declare(strict_types=1);

namespace App\Tests\Weather\Application\UseCase\Query\FetchAverageWeatherForLastSevenDays;

use App\Weather\Application\UseCase\Query\FetchAverage\FetchAverageWeatherForLastSevenDaysHandler;
use App\Weather\Application\UseCase\Query\FetchAverage\FetchAverageWeatherForLastSevenDaysQuery;
use App\Weather\Domain\DataTransfer\AverageWeatherDTO;
use App\Weather\Domain\Entity\Weather;
use App\Weather\Domain\Query\WeatherQueryInterface;
use App\Weather\Domain\Repository\WeatherRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use DateTimeImmutable;

class FetchAverageWeatherForLastSevenDaysTest extends KernelTestCase
{
    private WeatherRepositoryInterface $weatherRepository;
    private FetchAverageWeatherForLastSevenDaysHandler $handler;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->weatherRepository = $kernel->getContainer()->get(WeatherRepositoryInterface::class);
        $this->handler = new FetchAverageWeatherForLastSevenDaysHandler(
            $kernel->getContainer()->get(WeatherQueryInterface::class)
        );
    }

    public function testFetchWeatherForLastSevenDays(): void
    {
        $currentDate = (new DateTimeImmutable('today midnight'));

        $days = 7;

        $sumTemperature = 0.0;
        $sumWindSpeed = 0.0;

        for ($day = 0; $day < $days; $day++) {
            $temperature = random_int(0, 10) + (random_int(0, 10) / 10);
            $windSpeed = random_int(0, 10) + (random_int(0, 10) / 10);

            $this->weatherRepository->save(
                new Weather(
                    $currentDate->modify(sprintf('-%s day', $day))->format('Y-m-d H:i:s'),
                    'Riga',
                    $temperature,
                    $windSpeed
                )
            );

            $sumTemperature += $temperature;
            $sumWindSpeed += $windSpeed;
        }

        /** @var AverageWeatherDTO $weather */
        $weather = $this->handler->__invoke(new FetchAverageWeatherForLastSevenDaysQuery('Riga'));

        self::assertInstanceOf(AverageWeatherDTO::class, $weather);
        self::assertEquals('Riga', $weather->city);
        self::assertEquals(round($sumTemperature / $days, 2), $weather->temperature);
        self::assertEquals(round($sumWindSpeed / $days, 2), $weather->windSpeed);
    }

    public function testFetchWeatherOnlyForLastThreeDays(): void
    {
        $currentDate = (new DateTimeImmutable('today midnight'));

        $days = 3;

        $sumTemperature = 0.0;
        $sumWindSpeed = 0.0;

        for ($day = 0; $day < $days; $day++) {
            $temperature = random_int(0, 10) + (random_int(0, 10) / 10);
            $windSpeed = random_int(0, 10) + (random_int(0, 10) / 10);

            $this->weatherRepository->save(
                new Weather(
                    $currentDate->modify(sprintf('-%s day', $day))->format('Y-m-d H:i:s'),
                    'Riga',
                    $temperature,
                    $windSpeed
                )
            );

            $sumTemperature += $temperature;
            $sumWindSpeed += $windSpeed;
        }

        /** @var AverageWeatherDTO $weather */
        $weather = $this->handler->__invoke(new FetchAverageWeatherForLastSevenDaysQuery('Riga'));

        self::assertInstanceOf(AverageWeatherDTO::class, $weather);
        self::assertEquals('Riga', $weather->city);
        self::assertEquals(round($sumTemperature / $days, 2), $weather->temperature);
        self::assertEquals(round($sumWindSpeed / $days, 2), $weather->windSpeed);
    }

    public function testWeatherDataNotFound(): void
    {
        self::assertNull(
            $this->handler->__invoke(new FetchAverageWeatherForLastSevenDaysQuery('NotSupportedCity'))
        );
    }
}

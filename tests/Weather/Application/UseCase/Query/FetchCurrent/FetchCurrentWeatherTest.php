<?php

declare(strict_types=1);

namespace App\Tests\Weather\Application\UseCase\Query\FetchCurrent;

use App\Weather\Application\UseCase\Query\FetchCurrent\FetchCurrentWeatherHandler;
use App\Weather\Application\UseCase\Query\FetchCurrent\FetchCurrentWeatherQuery;
use App\Weather\Domain\DataTransfer\CurrentWeatherDTO;
use App\Weather\Domain\Entity\Weather;
use App\Weather\Domain\Query\WeatherQueryInterface;
use App\Weather\Domain\Repository\WeatherRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use DateTimeImmutable;

class FetchCurrentWeatherTest extends KernelTestCase
{
    private WeatherRepositoryInterface $weatherRepository;
    private FetchCurrentWeatherHandler $handler;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->weatherRepository = $kernel->getContainer()->get(WeatherRepositoryInterface::class);
        $this->handler = new FetchCurrentWeatherHandler(
            $kernel->getContainer()->get(WeatherQueryInterface::class)
        );
    }

    public function testCurrentWeatherFound(): void
    {
        $this->weatherRepository->save(
            new Weather(
                $date = (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s'),
                $city = 'Riga',
                $temperature = 10,
                $windSpeed = 10
            )
        );

        /** @var CurrentWeatherDTO $weather */
        $weather = $this->handler->__invoke(new FetchCurrentWeatherQuery('Riga'));

        self::assertInstanceOf(CurrentWeatherDTO::class, $weather);
        self::assertEquals($date, $weather->date);
        self::assertEquals($city, $weather->city);
        self::assertEquals($temperature, $weather->temperature);
        self::assertEquals($windSpeed, $weather->windSpeed);
    }

    public function testCurrentWeatherNotFound(): void
    {
        self::assertNull(
            $this->handler->__invoke(new FetchCurrentWeatherQuery('NotSupportedCity'))
        );
    }
}

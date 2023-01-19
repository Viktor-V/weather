<?php

declare(strict_types=1);

namespace App\Tests\Weather\Application\UseCase\Command\Save;

use App\Tests\TestHelper;
use App\Weather\Application\UseCase\Save\SaveWeatherCommand;
use App\Weather\Application\UseCase\Save\SaveWeatherHandler;
use App\Weather\Domain\Entity\Weather;
use App\Weather\Domain\Repository\WeatherRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use DateTimeImmutable;

class SaveWeatherHandlerTest extends KernelTestCase
{
    use TestHelper;

    private WeatherRepositoryInterface $weatherRepository;
    private SaveWeatherHandler $handler;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->weatherRepository = $kernel->getContainer()->get(WeatherRepositoryInterface::class);
        $this->handler = new SaveWeatherHandler($this->weatherRepository);
    }

    public function testNewWeatherData(): void
    {
        $this->handler->__invoke(new SaveWeatherCommand(
            $date = (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s'),
            $city = 'Riga',
            $temperature = 10,
            $windSpeed = 10
        ));

        /** @var Weather $weather */
        $weather = $this->weatherRepository->findByDateAndCity($date, $city);

        self::assertInstanceOf(Weather::class, $weather);
        self::assertEquals($date, self::getPropertyValue($weather, 'date'));
        self::assertEquals($city, self::getPropertyValue($weather, 'city'));
        self::assertEquals($temperature, self::getPropertyValue($weather, 'temperature'));
        self::assertEquals($windSpeed, self::getPropertyValue($weather, 'windSpeed'));
    }

    public function testUpdateWeatherData(): void
    {
        $this->weatherRepository->save(new Weather(
            $date = (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s'),
            $city = 'Riga',
            $temperature = 10,
            $windSpeed = 10
        ));

        /** @var Weather $weather */
        $weather = $this->weatherRepository->findByDateAndCity($date, $city);

        self::assertInstanceOf(Weather::class, $weather);
        self::assertEquals($date, self::getPropertyValue($weather, 'date'));
        self::assertEquals($city, self::getPropertyValue($weather, 'city'));
        self::assertEquals($temperature, self::getPropertyValue($weather, 'temperature'));
        self::assertEquals($windSpeed, self::getPropertyValue($weather, 'windSpeed'));

        $this->handler->__invoke(new SaveWeatherCommand(
            $date = (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s'),
            $city = 'Riga',
            $newTemperature = 15,
            $newWindSpeed = 5
        ));

        /** @var Weather $weather */
        $updatedWeather = $this->weatherRepository->findByDateAndCity($date, $city);

        self::assertInstanceOf(Weather::class, $weather);

        self::assertEquals(
            self::getPropertyValue($updatedWeather, 'date'),
            self::getPropertyValue($weather, 'date')
        );
        self::assertEquals(
            self::getPropertyValue($updatedWeather, 'city'),
            self::getPropertyValue($weather, 'city')
        );

        self::assertEquals($newTemperature, self::getPropertyValue($weather, 'temperature'));
        self::assertEquals($newWindSpeed, self::getPropertyValue($weather, 'windSpeed'));
    }
}

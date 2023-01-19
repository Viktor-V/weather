<?php

declare(strict_types=1);

namespace App\Tests\Weather\Domain\Entity;

use App\Tests\TestHelper;
use App\Weather\Domain\Entity\Weather;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class WeatherTest extends TestCase
{
    use TestHelper;

    public function testCreateWeatherEntity(): void
    {
        $weather = new Weather(
            $date = (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s'),
            $city = 'Riga',
            $temperature = 10.0,
            $windSpeed = 12.0
        );

        self::assertInstanceOf(Weather::class, $weather);

        self::assertEquals($date, self::getPropertyValue($weather, 'date'));
        self::assertEquals($city, self::getPropertyValue($weather, 'city'));
        self::assertEquals($temperature, self::getPropertyValue($weather, 'temperature'));
        self::assertEquals($windSpeed, self::getPropertyValue($weather, 'windSpeed'));
    }

    public function testUpdateWeatherEntity(): void
    {
        $weather = new Weather(
            $date = (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s'),
            $city = 'Riga',
            $temperature = 10.0,
            $windSpeed = 12.0
        );

        self::assertInstanceOf(Weather::class, $weather);

        self::assertEquals($date, self::getPropertyValue($weather, 'date'));
        self::assertEquals($city, self::getPropertyValue($weather, 'city'));
        self::assertEquals($temperature, self::getPropertyValue($weather, 'temperature'));
        self::assertEquals($windSpeed, self::getPropertyValue($weather, 'windSpeed'));

        $weather->update($newTemperature = 15.0, $newWindSpeed = 5.0);

        self::assertEquals($date, self::getPropertyValue($weather, 'date'));
        self::assertEquals($city, self::getPropertyValue($weather, 'city'));
        self::assertEquals($newTemperature, self::getPropertyValue($weather, 'temperature'));
        self::assertEquals($newWindSpeed, self::getPropertyValue($weather, 'windSpeed'));
    }
}

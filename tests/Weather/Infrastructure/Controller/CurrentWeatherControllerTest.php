<?php

declare(strict_types=1);

namespace App\Tests\Weather\Infrastructure\Controller;

use App\Tests\ClientTrait;
use App\Weather\Domain\Entity\Weather;
use App\Weather\Domain\Repository\WeatherRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;

class CurrentWeatherControllerTest extends WebTestCase
{
    use ClientTrait;

    public function testCurrentWeatherFound(): void
    {
        $client = $this->createClientWithLoggedInUser();

        self::getContainer()->get(WeatherRepositoryInterface::class)->save(
            new Weather(
                $date = (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s'),
                $city = 'Riga',
                $temperature = 10.0,
                $windSpeed = 10.0
            )
        );

        $client->request('GET', '/api/weather/current', ['city' => 'Riga']);

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);
        self::assertEquals($date, $responseData['currentWeather']['date']);
        self::assertEquals($city, $responseData['currentWeather']['city']);
        self::assertEquals($temperature, $responseData['currentWeather']['temperature']);
        self::assertEquals($windSpeed, $responseData['currentWeather']['windSpeed']);
    }

    public function testCurrentWeatherNotFound(): void
    {
        $client = $this->createClientWithLoggedInUser();

        $client->request('GET', '/api/weather/current', ['city' => 'Riga']);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}

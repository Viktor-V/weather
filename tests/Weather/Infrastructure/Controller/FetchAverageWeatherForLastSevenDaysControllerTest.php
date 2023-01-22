<?php

declare(strict_types=1);

namespace App\Tests\Weather\Infrastructure\Controller;

use App\Tests\ClientTrait;
use App\Weather\Domain\Entity\Weather;
use App\Weather\Domain\Repository\WeatherRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;

class FetchAverageWeatherForLastSevenDaysControllerTest extends WebTestCase
{
    use ClientTrait;

    public function testCurrentWeatherFound(): void
    {
        $client = $this->createClientWithLoggedInUser();

        $currentDate = (new DateTimeImmutable('today midnight'));

        $days = 7;

        $sumTemperature = 0.0;
        $sumWindSpeed = 0.0;

        for ($day = 0; $day < $days; $day++) {
            $temperature = random_int(0, 10) + (random_int(0, 10) / 10);
            $windSpeed = random_int(0, 10) + (random_int(0, 10) / 10);

            self::getContainer()->get(WeatherRepositoryInterface::class)->save(
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

        self::getContainer()->get(WeatherRepositoryInterface::class)->save(
            new Weather(
                $currentDate->modify('-8 day')->format('Y-m-d H:i:s'),
                'Riga',
                10.0,
                10.0
            )
        );

        self::getContainer()->get(WeatherRepositoryInterface::class)->save(
            new Weather(
                $currentDate->modify('-9 day')->format('Y-m-d H:i:s'),
                'Riga',
                7.0,
                7.0
            )
        );

        $client->request('GET', '/api/weather/last-seven-days', ['city' => 'Riga']);

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);
        self::assertEquals('Riga', $responseData['weather']['city']);
        self::assertEquals(round($sumTemperature / $days, 2), $responseData['weather']['temperature']);
        self::assertEquals(round($sumWindSpeed / $days, 2), $responseData['weather']['windSpeed']);
    }

    public function testCurrentWeatherNotFound(): void
    {
        $client = $this->createClientWithLoggedInUser();

        $client->request('GET', '/api/weather/last-seven-days', ['city' => 'Riga']);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}

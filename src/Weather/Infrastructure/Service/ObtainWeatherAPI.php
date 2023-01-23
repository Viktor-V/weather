<?php

declare(strict_types=1);

namespace App\Weather\Infrastructure\Service;

use App\Weather\Application\Service\ObtainWeatherAPIInterface;
use App\Weather\Application\UseCase\Command\Save\SaveWeatherCommand;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use DateTimeImmutable;

class ObtainWeatherAPI implements ObtainWeatherAPIInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private string $accessKey
    ) {
    }

    public function obtain(string $city): SaveWeatherCommand
    {
        $url = sprintf('http://api.weatherstack.com/current?access_key=%s&query=%s', $this->accessKey, $city);

        $response = $this->client->request('GET', $url);

        $weatherData = $response->toArray();

        return new SaveWeatherCommand(
            (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s'),
            $city,
            (float) $weatherData['current']['temperature'],
            (float) $weatherData['current']['wind_speed']
        );
    }
}

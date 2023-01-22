<?php

declare(strict_types=1);

namespace App\Tests\Stub;

use App\Weather\Application\Service\ObtainWeatherAPIInterface;
use App\Weather\Application\UseCase\Command\Save\SaveWeatherCommand;
use DateTimeImmutable;

class ObtainWeatherAPIStub implements ObtainWeatherAPIInterface
{
    public function obtain(string $city): SaveWeatherCommand
    {
        return new SaveWeatherCommand(
            (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s'),
            $city,
            10.0,
            10.0
        );
    }
}

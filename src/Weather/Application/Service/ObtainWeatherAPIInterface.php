<?php

declare(strict_types=1);

namespace App\Weather\Application\Service;

use App\Weather\Application\UseCase\Command\Save\SaveWeatherCommand;

interface ObtainWeatherAPIInterface
{
    public function obtain(string $city): SaveWeatherCommand;
}

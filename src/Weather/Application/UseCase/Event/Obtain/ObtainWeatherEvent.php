<?php

declare(strict_types=1);

namespace App\Weather\Application\UseCase\Event\Obtain;

readonly class ObtainWeatherEvent
{
    public function __construct(
        public string $city
    ) {
    }
}

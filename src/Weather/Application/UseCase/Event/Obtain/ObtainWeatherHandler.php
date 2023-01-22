<?php

declare(strict_types=1);

namespace App\Weather\Application\UseCase\Event\Obtain;

use App\Weather\Application\Service\ObtainWeatherAPIInterface;
use App\Weather\Application\UseCase\Command\Save\SaveWeatherHandler;

class ObtainWeatherHandler
{
    public function __construct(
        private ObtainWeatherAPIInterface $api,
        private SaveWeatherHandler $handler
    ) {
    }

    public function __invoke(ObtainWeatherEvent $message): void
    {
        $this->handler->__invoke($this->api->obtain($message->city));
    }
}

<?php

declare(strict_types=1);

namespace App\Weather\Infrastructure\Command;

use App\Weather\Application\UseCase\Event\Obtain\ObtainWeatherEvent;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:obtain:weather',
    description: 'Obtain weather data',
)]
class ObtainWeatherCommand extends Command
{
    public function __construct(
        private MessageBusInterface $bus,
        private array $cities,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->cities as $city) {
            $this->bus->dispatch(new ObtainWeatherEvent($city));
        }

        $io->success('Weather data obtained.');

        return Command::SUCCESS;
    }
}

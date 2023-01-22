<?php

declare(strict_types=1);

namespace App\Tests\Weather\Infrastructure\Command;

use App\Weather\Application\UseCase\Event\Obtain\ObtainWeatherEvent;
use App\Weather\Domain\Entity\Weather;
use App\Weather\Domain\Repository\WeatherRepositoryInterface;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class ObtainWeatherCommandTest extends KernelTestCase
{
    use InteractsWithMessenger;

    public function testExecute(): void
    {
        $kernel = self::bootKernel();

        $application = new Application($kernel);

        $command = $application->find('app:obtain:weather');
        $commandTester = new CommandTester($command);

        $this->messenger()->queue()->assertEmpty();

        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString('Weather data obtained.', $commandTester->getDisplay());

        $this->messenger()->queue()->assertNotEmpty();
        $this->messenger()->queue()->assertContains(ObtainWeatherEvent::class, 2);

        $this->messenger()->process(2);
        $this->messenger()->queue()->assertEmpty();

        $weatherRepository = $kernel->getContainer()->get(WeatherRepositoryInterface::class);

        $date = (new DateTimeImmutable('today midnight'))->format('Y-m-d H:i:s');

        /** @var Weather $rigaWeather */
        $rigaWeather = $weatherRepository->findByDateAndCity($date, 'Riga');
        /** @var Weather $newYorkWeather */
        $newYorkWeather = $weatherRepository->findByDateAndCity($date, 'New York');

        self::assertInstanceOf(Weather::class, $rigaWeather);
        self::assertInstanceOf(Weather::class, $newYorkWeather);
    }
}

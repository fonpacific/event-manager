<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Country;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Province;
use App\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:temporary:update-slug',
    description: 'Add a short description for your command',
)]
class TemporaryUpdateSlugCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->generateSlug(Event::class);
        $this->generateSlug(Province::class);
        $this->generateSlug(Country::class);
        $this->generateSlug(Place::class);

        $this->entityManager->flush();
        $io->success('Yo!');

        return Command::SUCCESS;
    }

    private function generateSlug(string $className): void
    {
        $entities = $this->entityManager->getRepository($className)->findAll();

        foreach ($entities as $entity) {
            $slug = Slugger::slug($entity->getName());
            $entity->setSlug($slug);
        }
    }
}

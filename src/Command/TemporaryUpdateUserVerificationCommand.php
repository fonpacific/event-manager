<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:temporary:update-user-verification',
    description: 'Add a short description for your command',
)]
class TemporaryUpdateUserVerificationCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->entityManager->getRepository(User::class)->findAll();

        foreach ($users as $user)
        {
            assert($user instanceof User);
            $user->setIsVerified(false);
        }

        $this->entityManager->flush();
        $io->success('Yo!');

        return Command::SUCCESS;
    }
}

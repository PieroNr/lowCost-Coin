<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:truc-random',
    description: 'Add a short description for your command',
)]
class TrucRandomCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('prenom', InputArgument::OPTIONAL, 'Votre prénom ici')
            ->addOption('uppercase', null, InputOption::VALUE_NONE, 'Je vais crier')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $prenom = $input->getArgument('prenom');

        if ($prenom) {
            $io->note(sprintf('Bonjour: %s', $prenom));
        }

        $randoms = [
            'francis',
            'saucisse',
            'chaise',
            'souris'
        ];

        $random = $randoms[array_rand($randoms)];

        if ($input->getOption('uppercase')) {
            $random = strtoupper($random);
        }

        $io->success($random);

        return Command::SUCCESS;
    }
}

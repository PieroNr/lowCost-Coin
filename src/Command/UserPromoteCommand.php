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
    name: 'app:user:promote',
    description: 'Add a short description for your command',
)]
class UserPromoteCommand extends Command
{
    private $om;

    public function __construct(EntityManagerInterface $om)
    {
        $this->om = $om;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Promouvoir un utilisateur')
            ->addArgument('email', InputArgument::REQUIRED, 'L\'Email de l\'utilisateur à promouvoir')
            ->addArgument('roles', InputArgument::REQUIRED, 'Le role à ajouter à l\'utilisateur')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $roles = $input->getArgument('roles');

        $userRepository = $this->om->getRepository(User::class);
        $user = $userRepository->findOneByEmail($email);

        if ($user) {
            $user->addRoles($roles);
            $this->om->flush();

            $io->success('Le role a été ajouté avec succès');
        } else {
            $io->error('Aucun utilisateur avec cet Email');
        }

        return 0;

    }
}

<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\ProgressBar;

#[AsCommand(
    name: 'app:delete-all-users',
    description: 'Deletes all users.',
)]
class DeleteAllUsersCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $question = new ConfirmationQuestion('Are you sure you want to delete ALL users? ', false);
        $helper = new QuestionHelper();
        if (!$helper->ask($input, $output, $question)) {
            $io->writeln('Aborting.');
            return Command::SUCCESS; // Well, technically I think it's not a failure.
        }

        $users = $this->userRepository->findAll();
        $count = count($users);
        $io->writeln('Deleting ' . $count . ' users');
        $progressBar = new ProgressBar($output, $count);
        $progressBar->start();

        foreach ($users as $user) {
            $this->entityManager->remove($user);
            $progressBar->advance();
        }

        $this->entityManager->flush();
        $progressBar->finish();

        $io->success('Successfully deleted all users.');

        return Command::SUCCESS;
    }
}

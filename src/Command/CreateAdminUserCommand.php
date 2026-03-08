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
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:create-admin-user',
    description: 'Creates a new admin user.',
)]
class CreateAdminUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Email address of the new admin user');
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ($user !== null) {
            $io->error("A valid user ({$user->getEmail()}) already exists.");
            return Command::FAILURE;
        }

        $question = new Question("Please enter a password for new user '{$email}': ");
        $question->setHidden(true);
        $helper = new QuestionHelper();
        $password = $helper->ask($input, $output, $question);
        $question = new Question("Please enter a name for new user '{$email}': ");
        $name = $helper->ask($input, $output, $question);

        try {
            $user = new User();
            $user->setEmail($email);
            $user->setName($name);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setRoles(['ROLE_ADMIN']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $io->error("Error creating user: {$exception->getMessage()}");
            return Command::FAILURE;
        }

        $io->success('Successfully created new user.');

        return Command::SUCCESS;
    }
}

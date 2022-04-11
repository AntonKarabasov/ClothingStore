<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class AddUserCommand extends Command
{
    protected static $defaultName = 'app:add-user';
    protected static $defaultDescription = 'Create user'; //Подправим описание

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $hasher;

    /**
     * @var \App\Repository\UserRepository
     */
    private UserRepository $userRepository;

    //Добавим конструктор
    public function __construct(string $name = null, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher, UserRepository $userRepository)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            // Добавим аргументы
            ->addOption('email', 'email', InputArgument::REQUIRED, 'Email')
            ->addOption('password', 'pass',InputArgument::REQUIRED, 'Password')
            ->addOption('isAdmin','', InputArgument::OPTIONAL, 'If set the user is create as an admin', 0)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        //Добавим проверку исполнения команды
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        // Добавим аргументы
        $email = $input->getOption('email');
        $password= $input->getOption('password');
        $isAdmin= $input->getOption('isAdmin');

        //Добавим немного интерфейса
        $io->title('Add user Command Wizard');
        $io->text([
            'Please, enter some information'
        ]);

        //Добавим проверки
        if (!$email){
            $email = $io->ask('Email');
        }

        if (!$password){
            $password = $io->askHidden('Password (your type will be hidden)');
        }

        if (!$isAdmin){
            $question = new Question('is admin? (1 or 0)', 0);
            $isAdmin = $io->askQuestion($question);
        }

        $isAdmin = (bool) $isAdmin;

        //Создадим пользователя
        try {
            $user = $this->createUser($email, $password, $isAdmin);
        } catch (\RuntimeException $exception) {
            $io->comment($exception->getMessage());

            return Command::FAILURE;
        }

        //Добавим текст успешной выполненной команды
        $successMessage = sprintf('%s was successfully created: %s',
          $isAdmin ? 'Administrator user' : 'User',
          $email
        );
        $io->success($successMessage);

        //Выведем информацию о выполнении команды
        $event = $stopwatch->stop('add-user-command');
        $stopwatchMessage = sprintf('New user\'s id: %s / Elapsed time: %.2f ms / Consumed memory: %.2f MB',
           $user->getId(),
          $event->getDuration(),
          $event->getMemory() / 1000 / 1000
        );
        $io->comment($stopwatchMessage);

        return Command::SUCCESS;
    }

    //Напишем метод для создания пользователя

    /**
     * @param string $email
     * @param string $password
     * @param bool   $isAdmin
     *
     * @return \App\Entity\User
     */
    private function createUser(string $email, string $password, bool $isAdmin): User
    {
        $existingUser = $this->userRepository->findBy(['email' => $email]);

        if ($existingUser) {
            throw new RuntimeException('User already exist');
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([$isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER']);

        $hashedPassword = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}

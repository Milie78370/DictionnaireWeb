<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Exception\RuntimeException;
use function PHPUnit\Framework\throwException;

#[AsCommand(
    name: 'app:add-user',
    description: 'Add a short description for your command',
)]
class AddUserCommand extends Command
{

    private $userRepository;
    private $passwordHasher;
    
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->setName('add-user')
            ->setDescription('Creation of user')
            ->addArgument('username', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, "user password")
            ->addOption('role', null, InputOption::VALUE_OPTIONAL, 'User role', 'admin')
            //->addArgument('role', InputArgument::REQUIRED, "user role")

        ;
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $role = $input->getOption('role');
        $user = new User();
        $user->setEmail($username);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);


        $this->userRepository->save($user, true);


        $output->writeln('user succesfully created');

        return Command::SUCCESS;
    }



}

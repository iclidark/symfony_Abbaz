<?php

namespace App\Command;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-client',
    description: 'Add a short description for your command',
)]
class CreateClientCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('firstname', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('lastname', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('email', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('phoneNumber', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('address', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper("question");

        $firstname = $input->getArgument('firstname');
        if (!$firstname) {
            $question = new Question("Please enter the firstname of the client: ");
            $firstname = $helper->ask($input, $output, $question);
        }

        $lastname = $input->getArgument('lastname');
        if (!$lastname) {
            $question = new Question("Please enter the lastname of the client: ");
            $lastname = $helper->ask($input, $output, $question);
        }

        $email = $input->getArgument('email');
        if (!$email) {
            $question = new Question("Please enter the email of the client: ");
            $email = $helper->ask($input, $output, $question);
        }

        $phoneNumber = $input->getArgument('phoneNumber');
        if (!$phoneNumber) {
            $question = new Question("Please enter the phone number of the client: ");
            $phoneNumber = $helper->ask($input, $output, $question);
        }

        $address = $input->getArgument('address');
        if (!$address) {
            $question = new Question("Please enter the address of the client: ");
            $address = $helper->ask($input, $output, $question);
        }

        $client = new Client();
        $client->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setPhoneNumber($phoneNumber)
            ->setAddress($address)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $io->success('You have successfully created a new client.');

        return Command::SUCCESS;
    }
}

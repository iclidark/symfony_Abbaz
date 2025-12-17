<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-products',
    description: 'Imports products from a CSV file.',
)]
class ImportProductsCommand extends Command
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
            ->addArgument('filepath', InputArgument::REQUIRED, 'The path to the CSV file.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filepath = $input->getArgument('filepath');

        if (!file_exists($filepath)) {
            $io->error(sprintf('File not found: %s', $filepath));
            return Command::FAILURE;
        }

        $handle = fopen($filepath, 'r');
        if ($handle === false) {
            $io->error(sprintf('Could not open file: %s', $filepath));
            return Command::FAILURE;
        }

        // Skip header row
        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $product = new Product();
            $product->setName($row[0]);
            $product->setDescription($row[1]);
            $product->setPrice($row[2]);

            $this->entityManager->persist($product);
        }

        fclose($handle);

        $this->entityManager->flush();

        $io->success('Products imported successfully.');

        return Command::SUCCESS;
    }
}

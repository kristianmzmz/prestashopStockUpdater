<?php

namespace ImporterBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use ImporterBundle\Controller\CsvProductProvider;
use ImporterBundle\Controller\CustomPrestashopWS;
use ImporterBundle\Entity\Product;
use ImporterBundle\Entity\ProductRepository;
use ImporterBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StockSynchronizeCommand extends ContainerAwareCommand
{
    const BATCH_NUMBER = 250;

    /** @var CsvProductProvider */
    private $csvReader;

    /**
     * Configuration of the command
     */
    protected function configure()
    {
        $this
            ->setName('stock:updater')
            ->setDescription('Updates the stock for the given CSV products');
    }

    /**
     * Main function. Executes the command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // First get the Repository to use it.
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $this->getContainer()->get('doctrine')->getManager();

        // Set the reader
        $this->csvReader = $this->getContainer()->get('importer_bundle.provider.csv_only_stock');

        /** @var ProductRepository $stockRepository */
        $stockRepository = $doctrine->getRepository('ImporterBundle:Stock');

        $output->writeln("Importando productos de CSV a Base de datos");
        $this->importProducts($output, $em);
        $output->writeln("");
        $output->writeln("");
        $output->writeln("Sincronizando Estocs a Prestashop");
        $this->exportStockToPrestashop($output, $stockRepository, $em);

        return true;
    }

    /**
     * Exports the database products to Prestashop
     *
     * @param OutputInterface $output
     * @param EntityManager   $em
     *
     * @return bool
     */
    protected function importProducts(OutputInterface $output, EntityManager $em)
    {
        $allCSVRows = $this->csvReader->getCSVRows();

        if (($productsCount = count($allCSVRows)) == 0) {
            $output->writeln("No hay productos para importar");

            return false;
        }

        // start and displays the progress bar
        // create a new progress bar (X units)
        $progress = new ProgressBar($output, $productsCount);

        $progress->start();

        foreach ($allCSVRows as $csvRow) {
            if ($this->csvReader->processCSVStockRow($csvRow)) {
                $progress->advance();
                if ($progress->getProgress() % self::BATCH_NUMBER == 0) {
                    $em->flush();
                }
            }
        }

        // Be sure we persist the final ones
        $em->flush();
        // ensure that the progress bar is at 100%
        $progress->finish();

        $output->writeln("");
        $output->writeln("");
        $output->writeln(sprintf("Se han importado del CSV %d/%d productos", $productsCount, $progress->getProgress()));

        return true;
    }

    /**
     * Exports the database products to Prestashop
     *
     * @param OutputInterface  $output
     * @param EntityRepository $stockRepository
     * @param EntityManager    $em
     */
    protected function exportStockToPrestashop(OutputInterface $output, EntityRepository $stockRepository, EntityManager $em)
    {
        /** @var CustomPrestashopWS $webService */
        $webService = $this->getContainer()->get('importer_bundle.web_service_factory')->getInstance();

        // Get all the products that have been updated
        $products = $stockRepository->findAll();
        $progress = new ProgressBar($output, count($products));

        // start and displays the progress bar
        $progress->start();

        /** @var Stock $productStock */
        foreach ($products as $productStock) {
            $result = $webService->updateStock($productStock);

            if ($result !== false) {
                $em->merge($productStock);
                $em->flush();
            }

            // advance the progress bar 1 unit
            $progress->advance();
        }

        // ensure that the progress bar is at 100%
        if ($progress->getMaxSteps() != $progress->getProgress()) {
            $progress->finish();
        }
    }
}
<?php

namespace ImporterBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use ImporterBundle\Controller\CsvProductProvider;
use ImporterBundle\Controller\CustomPrestashopWS;
use ImporterBundle\Entity\Product;
use ImporterBundle\Entity\ProductRepository;
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

        /** @var ProductRepository $productRepository */
        $productRepository = $doctrine->getRepository('ImporterBundle:Product');

        $output->writeln("Importando productos de CSV a Base de datos");
        $this->importProducts($output, $em);
        $output->writeln("");
        $output->writeln("");
        $output->writeln("Sincronizando Estocs a Prestashop");
        $this->exportProductsToPrestashop($output, $productRepository, $em);

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
     * @param EntityRepository $productRepository
     * @param EntityManager    $em
     */
    protected function exportProductsToPrestashop(
        OutputInterface $output,
        EntityRepository $productRepository,
        EntityManager $em
    ){
        /** @var CustomPrestashopWS $webService */
        $webService = $this->getContainer()->get('importer_bundle.web_service_factory')->getInstance();

        // Get all the products that have been updated
        $products = $productRepository->findBy(['updated' => 0]);
        $progress = new ProgressBar($output, count($products));

        // start and displays the progress bar
        $progress->start();

        // Foreach found products
        /** @var Product $product */
        foreach ($products as $product) {
            $productId = $product->getIdProduct();
            $actionType = $webService->getActionType($productId, $output);

            // In this importer we only want to update, not create
            switch ($actionType) {
                case CustomPrestashopWS::UPDATE_ACTION:
                    $result = $webService->updateStock($product);
                    break;
                case CustomPrestashopWS::CREATE_ACTION:
                case CustomPrestashopWS::ERROR_ACTION:
                default:
                    // We have to do something else?
                    $result = false;

                    $output->writeln("");
                    $output->writeln('Error en el producto: ' . $productId, true);
                    break;
            }

            if ($result !== false) {
                if(!isset($results[$actionType])){
                    $results[$actionType] = 0;
                }
                $results[$actionType]++;

                $product->setUpdated(true);
                if($result instanceof Product){
                    $product->setRealProductId($result->getRealProductId());
                }
                $em->merge($product);
                $em->flush();
            }

            // advance the progress bar 1 unit
            $progress->advance();
        }

        // ensure that the progress bar is at 100%
        if ($progress->getMaxSteps() != $progress->getProgress()) {
            $progress->finish();
        }

        // Display results
        $createdProducts = isset($results[CustomPrestashopWS::CREATE_ACTION])
            ? isset($results[CustomPrestashopWS::CREATE_ACTION])
            : 0;
        $output->writeln("");
        $output->writeln("Productos creados: " . $createdProducts);

        $uodatedProducts = isset($results[CustomPrestashopWS::UPDATE_ACTION])
            ? isset($results[CustomPrestashopWS::UPDATE_ACTION])
            : 0;
        $output->writeln("");
        $output->writeln("Productos actualizados: " . $uodatedProducts);
    }
}
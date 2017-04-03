<?php

namespace ImporterBundle\Command;

use ImporterBundle\Controller\CustomPrestashopWS;
use ImporterBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StockSynchronizeCommand extends ContainerAwareCommand
{
    const BATCH_NUMBER = 250;

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
        // Sincronise products
        $output->writeln("Sincronizando Estocs a Prestashop");
        $this->updateProducts($output);
        $output->writeln("");

        return true;
    }

    /**
     * Exports the database products to Prestashop
     *
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function updateProducts(OutputInterface $output)
    {
        $csvReader = $this->getContainer()->get('importer_bundle.provider.csv_only_stock');
        $allCSVRows = $csvReader->getCSVRows();

        if (($productsCount = count($allCSVRows)) == 0) {
            $output->writeln("No hay productos para importar");

            return false;
        }

        // start and displays the progress bar
        // create a new progress bar (X units)
        $progress = new ProgressBar($output, $productsCount);

        $progress->start();

        foreach ($allCSVRows as $csvRow) {
            $stock = $csvReader->processCSVStockRow($csvRow);
            if($this->exportStockToPrestashop($output, $stock)){
                $progress->advance();
            }
        }

        // ensure that the progress bar is at 100%
        $progress->finish();

        $output->writeln("");
        $output->writeln("");
        $output->writeln(sprintf("Se han importado del CSV %d/%d productos", $productsCount, $progress->getProgress()));

        return true;
    }

    /**
     * Updates the stock of the product.
     *
     * @param OutputInterface  $output
     * @param Stock            $productStock
     *
     * @return bool
     */
    protected function exportStockToPrestashop(OutputInterface $output, Stock $productStock)
    {
        /** @var CustomPrestashopWS $webService */
        $webService = $this->getContainer()->get('importer_bundle.web_service_factory')->getInstance();

        $productId = $productStock->getIdProduct();
        $actionType = $webService->getActionType($productId, $output);

        // In this importer we only want to update, not create
        switch ($actionType) {
            case CustomPrestashopWS::UPDATE_ACTION:
                $result = $webService->updateStock($productStock);
                break;
            case CustomPrestashopWS::CREATE_ACTION:
            case CustomPrestashopWS::ERROR_ACTION:
            default:
                // We have to do something else?
                $result = false;

                $output->writeln("");
                $output->writeln('Error en el producto: ' . $productId, true);
                $output->writeln("");
                break;
        }

        return $result;
    }
}
<?php

namespace Vendor\CustomAttribute\Console;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateProductAttribute extends Command
{
    private $state;
    private $productRepository;
    private $searchCriteriaBuilder;

    public function __construct(
        State $state,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->state = $state;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('vendor:customattribute:update')
             ->setDescription('Update custom attribute for all products')
             ->addArgument('value', InputArgument::REQUIRED, 'New Value');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
            $newValue = $input->getArgument('value');

            // Set page size for batch processing
            $pageSize = 10;
            $currentPage = 1;

            do {
                // Set the current page and page size
                $this->searchCriteriaBuilder->setPageSize($pageSize);
                $this->searchCriteriaBuilder->setCurrentPage($currentPage);
                $searchCriteria = $this->searchCriteriaBuilder->create();

                // Fetch products
                $productList = $this->productRepository->getList($searchCriteria);
                $products = $productList->getItems();

                foreach ($products as $product) {
                    $product->setCustomAttribute('custom_attribute', $newValue);
                    $this->productRepository->save($product);
                }
                $output->writeln($currentPage);
                $currentPage++;
            } while (count($products) == $pageSize); // Continue until no more products are found

            $output->writeln('<info>Custom attribute updated for all products successfully!</info>');
        } catch (LocalizedException $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Cli::RETURN_FAILURE;
        }

        return Cli::RETURN_SUCCESS;
    }
}
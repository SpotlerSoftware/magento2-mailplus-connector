<?php

namespace MailPlus\MailPlus\Console\Command;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\State;
use Magento\Store\Model\StoreManagerInterface;
use MailPlus\MailPlus\Helper\ConfigurationDataHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncProductsCommand extends Command {

	/**
	 * @param StoreManagerInterface $storeManager
	 */
	protected $_storeManager;
	
	/**
	 * @param ConfigurationDataHelper $dataHelper
	 */
	protected $_dataHelper;
	
	/**
	 * @param Collection $productCollection
	 */
	protected $_productCollection;
	
	const PAGESIZE = 200;
	
	
	/**
	 * @param State $state
	 * @param StoreManagerInterface $storeManager
	 * @param ConfigurationDataHelper $dataHelper
	 * @param Collection $productCollection
	 */
	public function __construct(State $state,
								StoreManagerInterface $storeManager,
								ConfigurationDataHelper $dataHelper,
								Collection $productCollection) {
		parent::__construct();
		
		// This must be set to prevent "Area not set" exceptions.
		$state->setAreaCode('frontend');
		
		$this->_storeManager = $storeManager;
		$this->_dataHelper = $dataHelper;
		$this->_productCollection = $productCollection; 
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName ( 'mailplus:sync-products' )->setDescription ( 'Sync all products to MailPlus' );
		parent::configure ();
	}
	/**
	 * {@inheritdoc}
	 */
	public function execute(InputInterface $input, OutputInterface $output) {
		$websites = $this->_storeManager->getWebsites ();
		foreach ( $websites as $website ) {
			$apiClient = $this->_dataHelper->getApiClient($website->getId());
			if ($apiClient == null) {
				$output->writeln ( '<info>Skipping website: ' . $website->getName () . '. MailPlus API not configured.</info>' );
				continue;
			}
			
			$output->writeln ( '<info>Syncing products for website: ' . $website->getName () . '</info>' );
			
			$stores = $website->getStores();
			
			foreach ($stores as $store) {
				if (!$this->_dataHelper->isEnabledForStore($store->getId())) {
					$output->writeln ( '<info>Skipping ' . $store->getName() . '. MailPlus connector not enabled.</info>' );
					continue;
				}
				
				$output->writeln ( '<info>Syncing products for storeview: ' . $store->getName () . '</info>' );
				$this->syncProductsForStore($store, $output);
			}
			
		}
	}
	
	/**
	 * 
	 * @param \Magento\Store\Model\Store $store
	 * @param \Symfony\Component\Console\Output\OutputInterface $outpuit
	 */
	protected function syncProductsForStore($store, $output) {
		$this->_storeManager->setCurrentStore($store);
		
		$collection = $this->_productCollection
			->addStoreFilter($store)
			->addAttributeToSelect('*')
			->setPageSize(self::PAGESIZE);
		
		$curPage = 1;
		$numDone = self::PAGESIZE;

		/**
		 * @var MailPlus\MailPlus\Helper\MailPlus\Api
		 */
		$api = $this->_dataHelper->getApiClient($store->getWebsite()->getId());
		
		while ($numDone == self::PAGESIZE) {
			$numDone = 0;
			$collection->clear()->setCurPage($curPage)->load()->addCategoryIds();

			foreach ($collection as $product) {
				$output->write("<info>.</info>");
			
				$api->syncProduct($product);
				$numDone++;
			}
			$curPage++;
		}
		$output->writeln("");
		
	}
}

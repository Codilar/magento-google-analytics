<?php
namespace  Codilar\Csvupload\Controller\Adminhtml\Csvupload;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
error_reporting(E_ALL ^ E_NOTICE);
ini_set('max_execution_time', 10800);

class Generate extends \Magento\Backend\App\Action
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory

    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }




    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        //$resultPage = $this->resultPageFactory->create();
        $backUrl = $this->getRequest()->getParam('backUrl');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $messageManager = $objectManager->get('Magento\Framework\Message\ManagerInterface');
        $vendorCatHelper = $objectManager->get('Codilar\Vendors\Helper\VendorCats');

        $ext = pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION);
        if($ext != "csv")
        {
            print_r("Invalid CSV File");
            exit;
        }

        $fileName = $_FILES['csvfile']['name'];
        $file = $_FILES['csvfile']['tmp_name'];
        $csv = array_map('str_getcsv', file($file));
        //$state = $objectManager->get('Magento\Framework\App\State');
        //$state->setAreaCode('frontend');
        //$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

        $filesystem = $objectManager->get('Codilar\Vendors\Model\Vendor\Image');
        $vendorImageDir = $filesystem->getBaseDir();

        $attribNames = $csv[0];
        unset($csv[0]);
        $values = $csv;
        foreach($values as $data1){
            $finalProductData[]=array_combine($attribNames, $data1);
        }
        $data2 = '';
        foreach($finalProductData as $data2)
        {
            $keyofCustOpt1 = '';
            $CustOpt1 = '';
            $CustOpt2 = '';
            $ind1 = '';
            $ind2 = '';
            $customOptionFinal = '';
            $categories = '';
            $category = '';
            $categoryArray = '';
            $collection = '';
            $_product = '';
            $optionInstance = '';
            $option = '';
            $_categoryFactory = '';
            $_vendorHelper = '';
            $vendorDetails = '';
            $vendorImage ='';
            $catMain = '';
            $catMainData = '';

            try {
                foreach (explode("|", $data2['custom_options']) as $keyofCustOpt1 => $CustOpt1) {
                    foreach (explode(",", $CustOpt1) as $CustOpt2) {
                        $ind1 = explode("=", $CustOpt2)[0];
                        $ind2 = explode("=", $CustOpt2)[1];
                        if ($ind1 == "option_title") {
                            $customOptionFinal[$keyofCustOpt1][$ind1] = $ind2;
                            $ind1 = 'title';
                        } elseif ($ind1 == "sku") {
                            $customOptionFinal[$keyofCustOpt1][$ind1] = $ind2;
                            $ind1 = 'sort_order';
                            $ind2 = 1;
                        }
                        if ($ind1 == 'title' || $ind1 == "price" || $ind1 == "price_type" || $ind1 == "sort_order") {
                            $customOptionFinal[$keyofCustOpt1][$ind1] = $ind2;
                        }
                    }
                }

                $_product = $objectManager->create('Magento\Catalog\Model\Product');
                $_product->setName($data2['name']);
                $_product->setTypeId('virtual');
                $_product->setAttributeSetId(4);
                $_product->setSku($data2['name'].'-'.$data2['sku']);
                $_product->setWebsiteIds(array(1));
                $_product->setVisibility(4);
                $_product->setPrice($data2['price']);
                $_product->setDescription($data2['description']);
                $_product->setVendorMainService($data2['vendor_main_service']);
                $_product->setServiceDuration($data2['service_duration']);
                $_product->setPriceLabel($data2['price_label']);
                $_product->setTaxClassId(0);
                //$url = preg_replace('#[^0-9a-z]+#i', '-', $data2['name'].$data2['sku']);
                //$url = strtolower($url);
                $url = urlencode($data2['vendor_main_service'].'-'.$data2['name'].'-'.$data2['sku']);
                $_product->setUrlKey($url);
                $_product->setStockData(array(
                        'use_config_manage_stock' => 0, //'Use config settings' checkbox
                        'manage_stock' => 1, //manage stock
                        'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                        'max_sale_qty' => 1, //Maximum Qty Allowed in Shopping Cart
                        'is_in_stock' => 1, //Stock Availability
                        'qty' => $data2['qty'] //qty
                    )
                );
                $_vendorHelper = $objectManager->get('Codilar\Vendors\Helper\Data');

                if ($data2['vendor_id']) {
                    $vendorDetails = $_vendorHelper->getVendorDetails($data2['vendor_id'])->getData();
                    $vendorImage = $vendorImageDir . $vendorDetails[0]['aprofile'];
                    //$_product->addImageToMediaGallery($vendorImage, array('image', 'small_image', 'thumbnail'), false, false);
                }

                $catMain =  explode(",", $data2['categories']);
                foreach($catMain as $catMainData){
                    $categories = explode("/", $catMainData);
                    $_categoryFactory = $objectManager->get('Magento\Catalog\Model\CategoryFactory');
                    foreach ($categories as $category) {
                        $collection = $_categoryFactory->create()->getCollection()->addAttributeToFilter('name', $category)->setPageSize(1);
                        if ($collection->getSize()) {
                            $categoryArray[] = $collection->getFirstItem()->getId();
                        }
                    }
                }
                if($data2['special_price']){
                    $_product->setSpecialPrice($data2['special_price']);
                    $_product->setSpecialFromDate($data2['special_price_from_date']);
                    $_product->setSpecialToDate($data2['special_price_to_date']) ;
                }
                $_product->setCategoryIds($categoryArray);

                if($customOptionFinal) {
                    $option = array(
                        'title' => 'Service Options',
                        'type' => 'radio',
                        'is_require' => 1,
                        'sort_order' => 1,
                        'store_id' =>1,
                        'values' => $customOptionFinal
                    );
                    $optionInstance = $_product->getOptionInstance()->unsetOptions();
                    $_product->setHasOptions(1);
                    $optionInstance->addOption($option);
                    $optionInstance->setProduct($_product);
                }
                $_product->save();

                if($data2['vendor_id'])
                {
                    $_vendorHelper->setVendorProducts($data2['vendor_id'], $_product->getId());
                    //$vendorCatHelper->setVendorCatRelation($data2['vendor_id'], $categoryArray);
                }
                print_r($_product->getId()."--Saved successfully");
                echo '<br>';
            }catch(Exception $e)
            {
                $messageManager->addError( __($e->getMessage()) );
               // $this->_redirect();
                echo '<br>';
                exit;
            }


        }
    }





    /**
     * Is the user allowed to view the blog post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }


}


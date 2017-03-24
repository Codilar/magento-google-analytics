<?php
/**
 * Created by Codilar Technologies Pvt Ltd.
 * User: Mohammad Mujassam
 * Date: 24/3/17
 * Time: 4:40 PM
 */

namespace Codilar\Csvupload\Observer\Adminhtml;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Backend\Model\Session;

class ProductSaveEvent implements ObserverInterface
{
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $this->session->setProductSaved(1);
    }
}
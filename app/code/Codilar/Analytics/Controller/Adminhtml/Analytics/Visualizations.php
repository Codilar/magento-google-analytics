<?php
/**
 * Created by Codilar Technologies Pvt Ltd.
 * User: Mohammad Mujassam
 * Date: 24/3/17
 * Time: 6:41 PM
 */
namespace  Codilar\Analytics\Controller\Adminhtml\Analytics;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Visualizations extends Action
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
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Codilar_Analytics::Analytics');
        $resultPage->getConfig()->getTitle()->prepend(__('Analytics'));
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }




}

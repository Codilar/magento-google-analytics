<?php
/**
 * Created by Codilar Technologies Pvt Ltd.
 * User: Mohammad Mujassam
 * Date: 24/3/17
 * Time: 6:41 PM
 */

namespace Codilar\Csvupload\Controller\Adminhtml\Analytics;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;

class Multiview extends Action
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
        $resultPage->setActiveMenu('Codilar_Csvupload::Csvupload');
        // $resultPage->addBreadcrumb(__('Blog Posts'), __('Blog Posts'));
        //  $resultPage->addBreadcrumb(__('Manage Blog Posts'), __('Manage Blog Posts'));
        $resultPage->getConfig()->getTitle()->prepend(__('Analytics'));
        $this->_view->loadLayout();
        $this->_view->renderLayout();
        //  return $resultPage;
    }

}
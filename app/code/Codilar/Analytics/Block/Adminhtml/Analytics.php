<?php

namespace Codilar\Analytics\Block\Adminhtml;

use Magento\Backend\Block\Template;

/**
 * Class Analytics
 * @package Codilar\Analytics\Block\Adminhtml
 */
class Analytics extends  Template
{

    /**
     * Analytics constructor.
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->_scopeConfig->getValue('codilar_analytics/analytics/client_id');
    }
}

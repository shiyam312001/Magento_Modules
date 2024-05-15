<?php

namespace MedizinhubCore\Home\Block;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;

class Home extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ProductCollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * Home constructor.
     *
     * @param Context                 $context
     * @param ProductCollectionFactory $productCollectionFactory
     * @param UrlInterface            $urlBuilder
     * @param array                   $data
     */
    public function __construct(
        Context $context,
        ProductCollectionFactory $productCollectionFactory,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context, $data);
    }

    /**
     * Get product collection by category IDs.
     *
     * @param array $ids
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollectionByCategories($ids)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $ids]);

        return $collection;
    }

    /**
     * Get dynamic ID.
     *
     * @return int
     */
    public function getDynamicId()
    {
        // Implement logic to retrieve the dynamic ID dynamically
        // For example, you can get it from a request parameter
        $request = $this->getRequest();
        return $request->getParam('dynamic_id', 9);
    }

    /**
     * Get dynamic URL.
     *
     * @return string
     */
    public function getDynamicUrl()
    {
        $dynamicId = $this->getDynamicId();
        return $this->_urlBuilder->getUrl('product_list/index/index', ['id' => $dynamicId]);
    }

    /**
     * Generate the product list link based on the dynamic ID.
     *
     * @return string
     */
    public function generateProductListLink()
    {
        $dynamicId = $this->getDynamicId();
        return $this->_urlBuilder->getUrl('product_list/index/index', ['id' => $dynamicId]);
    }

    /**
     * Prepare layout.
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        // $this->pageConfig->getTitle()->set(__(''));

        return parent::_prepareLayout();
    }
}

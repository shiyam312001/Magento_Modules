<?php
namespace MedizinhubCore\Discount\Model\Quote;

class Discount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    protected $calculator;
    protected $eventManager = null;
    protected $storeManager;
    protected $priceCurrency;

    public function __construct(
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\SalesRule\Model\Validator $validator,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    ) {
        $this->setCode('Discount');
        $this->eventManager = $eventManager;
        $this->calculator = $validator;
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }

        $oldPriceTotal = 0;
        $regularPriceTotal = 0;
        foreach ($items as $item) {
            $oldPrice = $item->getProduct()->getPrice();
            $regularPrice = $item->getProduct()->getData('special_price');
            $qty = $item->getQty();
            $oldPriceTotal += $oldPrice * $qty;
            $regularPriceTotal += $regularPrice * $qty;
        }

        $actualDiscount = $oldPriceTotal - $regularPriceTotal;
        $total->setDiscountAmount($actualDiscount);
        $total->setBaseDiscountAmount($actualDiscount);
        $total->setSortOrder(-1);

        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
{
    $result = null;
    $amount = -$total->getDiscountAmount(); // Add the minus sign here
    $formattedAmount = $this->priceCurrency->format(abs($amount), false); // Format the absolute amount
    $formattedAmount = '- ' . $formattedAmount; // Add the minus sign before the formatted amount
    $quoteSubtotal = $total->getSubtotal();
    $discountPercentage = ($amount / $quoteSubtotal) * 100;

    if ($amount != 0) {
        $result = [
            'code' => $this->getCode(),
            'title' => __('Actual Discount'),
            'value' => __('Free'),
            'class' => 'custom-discount-row',
            'percent' => round($discountPercentage, 2)
        ];
    }
    return $result;
}

}

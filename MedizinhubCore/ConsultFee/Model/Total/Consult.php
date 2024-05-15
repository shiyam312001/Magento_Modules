<?php
namespace MedizinhubCore\ConsultFee\Model\Total;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Psr\Log\LoggerInterface;

class Consult extends AbstractTotal
{
    protected $logger;
    protected $objectManager;
    protected $connection;
    protected $consultFee;

    public function __construct(
        LoggerInterface $logger,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->connection = $this->objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection();
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        // Fetch the custom fee value from the database
        $result1 = $this->connection->fetchOne("SELECT value FROM core_config_data WHERE path = 'customfee/customfee/customfee_amount'");

        // Check if the result is not empty
        if (!empty($result1)) {
            // Convert the fetched value to an integer
            $this->consultFee = (int)$result1;

            // Set the consult fee in the total
            $total->setTotalAmount('consult', $this->consultFee);
            $total->setBaseTotalAmount('consult', $this->consultFee);
            $total->setConsultFee($this->consultFee);
            $total->setBaseConsultFee($this->consultFee);

            // Update the grand total with the consult fee
            $grandTotal = $total->getGrandTotal();
            $baseGrandTotal = $total->getBaseGrandTotal();
            $total->setGrandTotal($grandTotal + $this->consultFee);
            $total->setBaseGrandTotal($baseGrandTotal + $this->consultFee);
        }

        return $this;
    }

    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        // Check if condition is true
        $condition = true; // Replace this with your actual condition

        // If condition is true, return the consult fee, else return null
        if ($condition) {
            return [
                'code' => 'consult',
                'title' => __('Consult Fee'),
                'value' => $this->consultFee // Use the stored consult fee
            ];
        } else {
            return null;
        }
    }

    public function getLabel()
    {
        return __('Consult Fee');
    }
}

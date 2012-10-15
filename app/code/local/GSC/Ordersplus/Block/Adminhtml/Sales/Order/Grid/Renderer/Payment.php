<?php

/**
 * @category    Graphic Sourcecode
 * @package     GSC_Ordersplus
 * @license     http://www.graphicsourcecode.com/gs-license.txt
 * @author      Geoff Safcik <info@graphicsourcecode.com>
 */
 
class GSC_Ordersplus_Block_Adminhtml_Sales_Order_Grid_Renderer_Payment extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$paymentMethodCode = $row->getPaymentMethod();
		$paymentTitle = Mage::getStoreConfig('payment/' . $paymentMethodCode . '/title');
		
		return $paymentTitle;
	}
	
	public function getPaymentMethods()
	{
		$payments = Mage::getSingleton('payment/config')->getActiveMethods();

		foreach ($payments as $paymentCode => $paymentModel) {
			$paymentTitle = Mage::getStoreConfig('payment/' . $paymentCode . '/title');
			$methods[$paymentCode] = $paymentTitle;
		}

		return $methods;
	}
}
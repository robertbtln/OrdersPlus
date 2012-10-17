<?php  

/**
 * @category    Graphic Sourcecode
 * @package     GSC_Ordersplus
 * @license     http://www.graphicsourcecode.com/gs-license.txt
 * @author      Geoff Safcik <info@graphicsourcecode.com>
 */
  
class GSC_Ordersplus_Model_Observer  
{      
    /** 
     * Add a customer order comment when the order is placed 
     * @param object $event 
     * @return  
     */  
    public function setOrderComment(Varien_Event_Observer $observer)  
    {  
        $_order = $observer->getEvent()->getOrder();  
        $_request = Mage::app()->getRequest();  
          
        $_comments = strip_tags($_request->getParam('gscOrderComment'));  
  
        if(!empty($_comments)){  
            $_order->setCustomerNote($_comments);              
        }          
          
        return $this;          
    }  
}  
<?php

class GSC_Ordersplus_Block_Adminhtml_Sales_Order_Create_Search_Renderer_Stock extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $stock_qty = $row->getData($this->getColumn()->getIndex());
        
        $stock = sprintf('<div style="background:#daeae2!important; text-align:center;">%d</div>', $stock_qty);
        return $stock;
    }
}

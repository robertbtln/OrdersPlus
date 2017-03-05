<?php

/**
 * @category    Graphic Sourcecode
 * @package     GSC_Ordersplus
 * @license     http://www.graphicsourcecode.com/gs-license.txt
 * @author      Geoff Safcik <info@graphicsourcecode.com>
 */

class GSC_Ordersplus_Model_Mysql4_Order_Collection extends Mage_Sales_Model_Mysql4_Order_Collection
{
    public function getSelectCountSql()
    {
        /* @var $countSelect Varien_Db_Select */
        $countSelect = parent::getSelectCountSql();

        $countSelect->reset(Zend_Db_Select::GROUP);

        $countSelect->resetJoinLeft();
        return $countSelect;
    }
}

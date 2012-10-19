<?php

/**
 * @category    Graphic Sourcecode
 * @package     GSC_Ordersplus
 * @license     http://www.graphicsourcecode.com/gs-license.txt
 * @author      Geoff Safcik <info@graphicsourcecode.com>
 */

class GSC_Ordersplus_Block_Adminhtml_Sales_Order_Grid_Renderer_Comments extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
      //var_dump($row->getData()); 
      $comments = $row->getData('comment');

      $words = array('NULL', 'Captured amount of','Authorize.Net Transaction ID','authorize and capture','Authorized amount of','Capturing amount of');
      $match_expression = implode("|", $words);
 
      if (!preg_match('/'.$match_expression.'/i', $comments)) {
          return $comments;
      }
      
    }

}

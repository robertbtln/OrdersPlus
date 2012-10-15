<?php

/**
 * @category    Graphic Sourcecode
 * @package     GSC_Ordersplus
 * @license     http://www.graphicsourcecode.com/gs-license.txt
 * @author      Geoff Safcik <info@graphicsourcecode.com>
 */

class GSC_Ordersplus_Block_Adminhtml_Sales_Order_Grid_Renderer_Products extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $skus = explode('|', $row->getSkus());
        $qtys = explode('|', $row->getQtys());
        $names = explode('|', $row->getNames());
        $html = '';

        if($this->getColumn()->getRenderColumn() == 'names') {
            $html .= sprintf('<tr title="%s" style="cursor:default;"><td><div style="font-weight:bold; margin-bottom:5px; color:#202020;">- Total Quantity -</div></td><td style="width:1em; font-weight:bold; color:#202020;">%s</td></tr>', $sku, array_sum($qtys), trim($names[$i]), $sku, trim($qtys[$i]));
            $show = Mage::getStoreConfig('ordersplus/orderinfocolumns/product_show');
            $j = 0;
              
            foreach ($skus as $i => $sku) {
                if($j <= $show-1) {
                    if($qtys[$i] == round($qtys[$i],0)) {
                        $html .= sprintf('<tr title="%s" style="cursor:default;"><td><div style="font-weight:bold;">%s</div><div style="margin-top:8px;"><span style="font-weight:bold;">SKU:</span>%s</div></td><td style="width:1em;">%d</td></tr>', $sku, trim($names[$i]), $sku, trim($qtys[$i]));
                    } else {
                       $html .= sprintf('<tr title="%s" style="cursor:default;"><td><div style="font-weight:bold;">%s</div><div style="margin-top:8px;"><span style="font-weight:bold;">SKU:</span>%s</div></td><td style="width:1em;">%.4f</td></tr>', $sku, trim($names[$i]), $sku, trim($qtys[$i]));
                    }
                }
                $j++; 
            }

            if($j > $show) {
                $html .= '<tr><td colspan="2"><div style="font-style:italic; color:#202020;">--- more item(s) ---</div></td></tr>';
            }
        }

        return '<table style="border: 0; border-collapse: collapse; color:#3e3e3e;"><tbody>'.$html.'</tbody></table>';
      }

}

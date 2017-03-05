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
        $orderData = $row->getData();

        $skus = explode('|||', $orderData['skus']);
        $qtys = explode('|||', $orderData['qtys']);
        $names = explode('|||', $orderData['names']);
        // Get the number of products to show
        $show = Mage::getStoreConfig('ordersplus/orderinfocolumns/product_show');
        $skuCount = count($skus);

        $html = '<table id="gsc-products-admin-table"><tbody>';
        if ($this->getColumn()->getRenderColumn() == 'names') {  // TODO remove as not needed?
            $html .= sprintf('<tr>
                                  <td>
                                      <div class="bold">- Total Quantity -</div>
                                  </td>  
                                  <td>
                                    <div class="bold">%s</div>
                                  </td>
                              </tr>', array_sum($qtys));

            foreach ($skus as $key => $value) {
                if ($key + 1 > $show) {
                    continue;
                }

                $html .= sprintf('<tr>
                                      <td>
                                          <div class="bold">%s</div>
                                          <div>
                                              <span class="bold">SKU:</span> %s
                                          </div>
                                      </td>
                                      <td>
                                        <div>%d</div>
                                      </td>
                                  </tr>',
                                  trim($names[$key]),
                                  $skus[$key],
                                  trim($qtys[$key]));
            }

            if ($skuCount > $show) {
                $html .= sprintf('<tr>
                                      <td colspan="2">
                                          <div class="italic">--- (%s) more item%s ---</div>
                                      </td>
                                  </tr>', $skuCount - $show, (($skuCount - $show) > 1 ? 's' : ''));
            }
        }

        return $html . '</tbody></table>';
      }

}

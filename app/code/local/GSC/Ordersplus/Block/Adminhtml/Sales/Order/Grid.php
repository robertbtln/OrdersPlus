<?php

/**
 * @category    Graphic Sourcecode
 * @package     GSC_Ordersplus
 * @license     http://www.graphicsourcecode.com/gs-license.txt
 * @author      Geoff Safcik <info@graphicsourcecode.com>
 */

class GSC_Ordersplus_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());

        $collection->getSelect()->joinLeft(array('sfoi' => 'sales_flat_order_item'),'main_table.entity_id = sfoi.order_id AND sfoi.parent_item_id IS NULL', array(
            'skus'  => new Zend_Db_Expr('group_concat(sfoi.sku SEPARATOR " | ")'),
            'qtys'  => new Zend_Db_Expr('group_concat(sfoi.qty_ordered SEPARATOR " | ")'),
            'names' => new Zend_Db_Expr('group_concat(sfoi.name SEPARATOR " | ")'),
        ));
        $collection->getSelect()->group('entity_id');
        $collection->getSelect()->joinLeft(array('sfog' => 'sales_flat_order_grid'),'main_table.entity_id = sfog.entity_id',array('sfog.shipping_name','sfog.billing_name'));
        $collection->getSelect()->joinLeft(array('sfo'=>'sales_flat_order'),'sfo.entity_id=main_table.entity_id',array('sfo.customer_email','sfo.weight','sfo.discount_description','sfo.increment_id','sfo.store_id','sfo.created_at','sfo.status','sfo.base_grand_total','sfo.grand_total','shipping_description','sfo.total_item_count'));
        $collection->getSelect()->joinLeft(array('sfoa'=>'sales_flat_order_address'),'main_table.entity_id = sfoa.parent_id AND sfoa.address_type="shipping"',array('sfoa.street','sfoa.city','sfoa.region','sfoa.postcode','sfoa.telephone','sfoa.country_id'));
        $collection->getSelect()->joinLeft(array('sfop' => 'sales_flat_order_payment'),'main_table.entity_id = sfop.parent_id',array('sfop.method'));
        $collection->getSelect()->joinLeft(array('sfosh' => 'sales_flat_order_status_history'),'main_table.entity_id = sfosh.parent_id',array('sfosh.comment'));

        $collection->getSelect()->group('entity_id');
        
        $this->setCollection($collection);
        
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        // ORIGINAL COLUMNS

        $this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
            'filter_index' => 'sfo.increment_id',
        ));

        if (Mage::getStoreConfig('ordersplus/originalcolumns/store_id'))
        {
            if (!Mage::app()->isSingleStoreMode()) {
                $this->addColumn('store_id', array(
                    'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
                    'index'     => 'store_id',
                    'filter_index' => 'sfo.store_id',
                    'type'      => 'store',
                    'store_view'=> true,
                    'display_deleted' => true,
                ));
            }
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'filter_index' => 'sfo.created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        if (Mage::getStoreConfig('ordersplus/originalcolumns/billing_name'))
        {
            $this->addColumn('billing_name', array(
                'header' => Mage::helper('sales')->__('Bill to Name'),
                'index' => 'billing_name',
                'filter_index' => 'sfog.billing_name',
            ));
        }
            
        if (Mage::getStoreConfig('ordersplus/originalcolumns/shipping_name'))
        {
            $this->addColumn('shipping_name', array(
                'header' => Mage::helper('sales')->__('Ship to Name'),
                'index' => 'shipping_name',
                'filter_index' => 'sfog.shipping_name',
            ));
        }

        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'filter_index' => 'sfo.base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));

        if (Mage::getStoreConfig('ordersplus/originalcolumns/grand_total'))
        {
            $this->addColumn('grand_total', array(
                'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
                'index' => 'grand_total',
                'filter_index' => 'sfo.grand_total',
                'type'  => 'currency',
                'currency' => 'order_currency_code',
            ));
        }

        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'filter_index' => 'sfo.status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));

        // ADDITIONAL: ORDER BREAKDOWN (INFORMATION)

        if (Mage::getStoreConfig('ordersplus/orderinfocolumns/discount_description'))
        {
            $this->addColumn('discount_description', array(
                    'header' => Mage::helper('sales')->__('Coupon Code'), // Not included in Order Export (don't know why but not important for our needs)
                    'index' => 'discount_description',
                    'filter_index' => 'sfo.discount_description',
                    'width' => '50px',
            ));
        }

        if (Mage::getStoreConfig('ordersplus/orderinfocolumns/payment_method'))
        {
            $payment = new GSC_Ordersplus_Block_Adminhtml_Sales_Order_Grid_Renderer_Payment();

            $this->addColumn('method', array(
                    'header' => Mage::helper('sales')->__('Payment Method'),
                    'index' => 'method',
                    'filter_index' => 'sfop.method',
                    'type'  => 'options',
                    'options' => $payment->getPaymentMethods(),
            ));
        }

        if (Mage::getStoreConfig('ordersplus/orderinfocolumns/product_information'))
        {
            $this->addColumn('names', array(
                'header'    => Mage::helper('Sales')->__('Product Information'),
                'index'     => 'names',
                'renderer'  => 'GSC_Ordersplus_Block_Adminhtml_Sales_Order_Grid_Renderer_Products',
                'filter_index' => 'sfoi.name',
                'render_column' => 'names',
                'sortable'  => FALSE,
                'type'        => 'text',
            ));
        }

        if (Mage::getStoreConfig('ordersplus/orderinfocolumns/comment'))
        {
            $this->addColumn('comment', array(
                    'header' => Mage::helper('sales')->__('Customer Comment'),
                    'index' => 'comment',
                    'renderer'  => 'GSC_Ordersplus_Block_Adminhtml_Sales_Order_Grid_Renderer_Comments',
                    'filter_index' => 'sfosh.comment',
                    'width' => '70px',
            ));
        }

        if (Mage::getStoreConfig('ordersplus/orderinfocolumns/shipping_description'))
        {
            $this->addColumn('shipping_description', array(
                    'header' => Mage::helper('sales')->__('Shipping Method'),
                    'index' => 'shipping_description',
                    'filter_index' => 'sfo.shipping_description',
                    'width' => '50px',
            ));
        }

        if (Mage::getStoreConfig('ordersplus/orderinfocolumns/weight'))
        {
            $this->addColumn('weight', array(
                    'header' => Mage::helper('sales')->__('Expected Package<br />Weight'),
                    'index' => 'weight',
                    'filter_index' => 'sfo.weight',
                    'width' => '50px',
            ));
        }

        // ADDITIONAL: CUSTOMER INFORMATION

        if (Mage::getStoreConfig('ordersplus/customerinfocolumns/customer_email'))
        {
            $this->addColumn('customer_email', array(
                    'header' => Mage::helper('sales')->__('Customer Email'),
                    'index' => 'customer_email',
                    'filter_index' => 'sfo.customer_email',
                    'width' => '50px',
            ));
        }

        if (Mage::getStoreConfig('ordersplus/customerinfocolumns/street'))
        {
            $this->addColumn('street', array(
                    'header' => Mage::helper('sales')->__('Shipping<br />Street Address'),
                    'index' => 'street',
                    'filter_index' => 'sfoa.street',
                    'width' => '50px',
            ));
        }

        if (Mage::getStoreConfig('ordersplus/customerinfocolumns/city'))
        {
            $this->addColumn('city', array(
                    'header' => Mage::helper('sales')->__('Shipping<br />City'),
                    'index' => 'city',
                    'filter_index' => 'sfoa.city',
                    'width' => '50px',
            ));
        }

        if (Mage::getStoreConfig('ordersplus/customerinfocolumns/region'))
        {
            $this->addColumn('region', array(
                    'header' => Mage::helper('sales')->__('Shipping<br />State/Region'),
                    'index' => 'region',
                    'filter_index' => 'sfoa.region',
                    'width' => '50px',
            ));
        }

        if (Mage::getStoreConfig('ordersplus/customerinfocolumns/postcode'))
        {
            $this->addColumn('postcode', array(
                    'header' => Mage::helper('sales')->__('Shipping<br />Zip/Postal'),
                    'index' => 'postcode',
                    'filter_index' => 'sfoa.postcode',
                    'width' => '50px',
            ));
        }

        if (Mage::getStoreConfig('ordersplus/customerinfocolumns/telephone'))
        {
            $this->addColumn('telephone', array(
                    'header' => Mage::helper('sales')->__('Phone Number'),
                    'index' => 'telephone',
                    'filter_index' => 'sfoa.telephone',
                    'width' => '50px',
            ));
        }
        

        if (Mage::getStoreConfig('ordersplus/customerinfocolumns/country_id'))
        {
            $this->addColumn('country_id', array(
                    'header' => Mage::helper('sales')->__('Shipping<br />Country'), 
                    'index' => 'country_id',
                    'filter_index' => 'sfoa.country_id',
                    'width' => '50px',
            ));
        }


        // ORIGINAL COLUMNS

        if (Mage::getStoreConfig('ordersplus/originalcolumns/view'))
        {
            if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
                $this->addColumn('action',
                    array(
                        'header'    => Mage::helper('sales')->__('Action'),
                        'width'     => '50px',
                        'type'      => 'action',
                        'getter'     => 'getId',
                        'actions'   => array(
                            array(
                                'caption' => Mage::helper('sales')->__('View'),
                                'url'     => array('base'=>'*/sales_order/view'),
                                'field'   => 'order_id'
                            )
                        ),
                        'filter'    => false,
                        'sortable'  => false,
                        'index'     => 'stores',
                        'is_system' => true,
                ));
            }
        }
        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/cancel')) {
            $this->getMassactionBlock()->addItem('cancel_order', array(
                 'label'=> Mage::helper('sales')->__('Cancel'),
                 'url'  => $this->getUrl('*/sales_order/massCancel'),
            ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/hold')) {
            $this->getMassactionBlock()->addItem('hold_order', array(
                 'label'=> Mage::helper('sales')->__('Hold'),
                 'url'  => $this->getUrl('*/sales_order/massHold'),
            ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/unhold')) {
            $this->getMassactionBlock()->addItem('unhold_order', array(
                 'label'=> Mage::helper('sales')->__('Unhold'),
                 'url'  => $this->getUrl('*/sales_order/massUnhold'),
            ));
        }

        $this->getMassactionBlock()->addItem('pdfinvoices_order', array(
             'label'=> Mage::helper('sales')->__('Print Invoices'),
             'url'  => $this->getUrl('*/sales_order/pdfinvoices'),
        ));

        $this->getMassactionBlock()->addItem('pdfshipments_order', array(
             'label'=> Mage::helper('sales')->__('Print Packingslips'),
             'url'  => $this->getUrl('*/sales_order/pdfshipments'),
        ));

        $this->getMassactionBlock()->addItem('pdfcreditmemos_order', array(
             'label'=> Mage::helper('sales')->__('Print Credit Memos'),
             'url'  => $this->getUrl('*/sales_order/pdfcreditmemos'),
        ));

        $this->getMassactionBlock()->addItem('pdfdocs_order', array(
             'label'=> Mage::helper('sales')->__('Print All'),
             'url'  => $this->getUrl('*/sales_order/pdfdocs'),
        ));

        return $this; 
    }

    public function getRowUrl($row)
    {
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
        }
        return false;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}

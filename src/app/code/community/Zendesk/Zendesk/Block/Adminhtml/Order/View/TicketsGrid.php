<?php

/**
 * Copyright 2012 Zendesk.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class Zendesk_Zendesk_Block_Adminhtml_Order_View_TicketsGrid extends Mage_Adminhtml_Block_Widget_Grid

{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ZendeskSalesOrderGrid');
        $this->setEmptyText($this->__('No items'));
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('all_tickets');
        $this->setRowClickCallback(false);
        //$this->setDefaultSort('increment_id', 'DESC');
        // $this->setTemplate('zendesk/order/tickets.phtml');
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $tickets = array();
        try {
            //$tickets = Mage::getModel('zendesk/api_tickets')->forOrder($this->getOrder()->getIncrementId());
            $tickets = Mage::getModel('zendesk/api_tickets')->forRequester($this->getOrder()->getCustomerEmail());
        } catch (Exception $e) {
            // Don't do anything, just don't show the tickets
        }
        $collection = new Varien_Data_Collection();
        foreach ($tickets as $ticket) {
            // @todo <?php $t = Mage::getModel('zendesk/api_tickets')->get($ticket['id'], true); ?>
            $collection->addItem(new Varien_Object($ticket));
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @todo refactor each renderer
     * @return $this
     */
    protected function _prepareColumns()
    {

        $this->addColumn('priority', array(
            'header'   => Mage::helper('zendesk')->__('Priority'),
            'index'    => 'priority',
            'filter'   => false,
            'sortable' => false
        ));
        $this->addColumn('subject', array(
            'header'   => Mage::helper('zendesk')->__('Subject'),
            'index'    => 'subject',
            'filter'   => false,
            'sortable' => false
        ));

        $this->addColumn('created_at', array(
            'header'   => Mage::helper('zendesk')->__('Requested'),
            'index'    => 'created_at',
            'type'     => 'datetime',
            'filter'   => false,
            'sortable' => false
        ));
        $this->addColumn('updated_at', array(
            'header'   => Mage::helper('zendesk')->__('Updated'),
            'index'    => 'updated_at',
            'type'     => 'datetime',
            'filter'   => false,
            'sortable' => false
        ));
        $this->addColumn('status', array(
            'header'   => Mage::helper('zendesk')->__('Status'),
            'index'    => 'status',
            'filter'   => false,
            'sortable' => false
        ));
        $this->addColumn('status', array(
            'header'   => Mage::helper('zendesk')->__('Status'),
            'index'    => 'status',
            'filter'   => false,
            'sortable' => false
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param Varien_Object $row
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return false;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/zendesk/salesOrderViewTicketGrid', array('order_id' => $this->getOrderIdFromRequest()));
    }

    /**
     * @return int
     */
    public function getOrderIdFromRequest()
    {
        return (int)Mage::app()->getRequest()->getParam('order_id', 0);
    }

    /**
     * Retrieve available order
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if ($this->hasOrder()) {
            return $this->getData('order');
        }
        $this->setData('order', Mage::getModel('sales/order')->load($this->getOrderIdFromRequest()));
        return $this->getData('order');
    }
}

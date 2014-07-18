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
        $this->setEmptyText($this->__('No tickets found for order %s', $this->getOrder()->getIncrementId()));
        $this->setSaveParametersInSession(true);
        $this->setFilterVisibility(false);
        $this->setUseAjax(true);
        $this->setRowClickCallback(false);
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = new Varien_Data_Collection();
        $tickets    = array();
        try {
            $tickets = Mage::getModel('zendesk/api_tickets')->forOrder($this->getOrder()->getIncrementId());
        } catch (Exception $e) {
            // Don't do anything, just don't show the tickets
        }
        if (false !== $tickets) {
            foreach ($tickets as $ticket) {
                $t    = Mage::getModel('zendesk/api_tickets')->get($ticket['id'], true);
                $item = new Varien_Object($ticket);
                $item->setTicketDetail($t);
                $collection->addItem($item);
            }
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
        $this->addColumn('ticket_detail_group', array(
            'header'   => Mage::helper('zendesk')->__('Group'),
            'index'    => 'ticket_detail_group',
            'renderer' => 'zendesk/adminhtml_order_widget_grid_column_renderer_ticketGroup',
            'filter'   => false,
            'sortable' => false
        ));

        $this->addColumn('ticket_detail_assignee', array(
            'header'   => Mage::helper('zendesk')->__('Assignee'),
            'index'    => 'ticket_detail_assignee',
            'renderer' => 'zendesk/adminhtml_order_widget_grid_column_renderer_ticketAssignee',
            'filter'   => false,
            'sortable' => false
        ));

        $this->addColumn('action',
            array(
                'header'   => Mage::helper('catalog')->__('Action'),
                'width'    => '50px',
                'type'     => 'action',
                'renderer' => 'zendesk/adminhtml_order_widget_grid_column_renderer_viewTicket',
                'filter'   => false,
                'sortable' => false,
                'index'    => 'product_id',
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

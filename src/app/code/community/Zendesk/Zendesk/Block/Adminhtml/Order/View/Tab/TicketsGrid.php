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
class Zendesk_Zendesk_Block_Adminhtml_Order_View_Tab_TicketsGrid extends Zendesk_Zendesk_Block_Adminhtml_Grid_AbstractTicketsGrid

{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ZendeskSalesOrderGrid');
        $this->setEmptyText($this->__('No tickets found for order %s', $this->getOrder()->getIncrementId()));
    }

    protected function _getTickets()
    {
        $tickets = array();
        try {
            $tickets = Mage::getModel('zendesk/api_tickets')->forOrder($this->getOrder()->getIncrementId());
        } catch (Exception $e) {
            // Don't do anything, just don't show the tickets
        }
        return $tickets;
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

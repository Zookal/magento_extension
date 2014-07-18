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
class Zendesk_Zendesk_Block_Adminhtml_Customer_Edit_TicketsGrid extends Zendesk_Zendesk_Block_Adminhtml_Grid_AbstractTicketsGrid

{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ZendeskCustomerEditGrid');
        $this->setEmptyText($this->__('No tickets found for customer %s', $this->getCustomer()->getEmail()));
    }

    protected function _getTickets()
    {
        $tickets = array();
        try {
            $tickets = Mage::getModel('zendesk/api_tickets')->forRequester($this->getCustomer()->getEmail());
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
        return $this->getUrl('*/zendesk/customerEditTicketGrid', array('customer_id' => $this->getCustomer()->getId()));
    }

    /**
     * @return int
     */
    public function getCustomerIdFromRequest()
    {
        return (int)Mage::app()->getRequest()->getParam('customer_id', 0);
    }

    /**
     * Retrieve customer object
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {

        if ($this->hasCustomer()) {
            return $this->getData('customer');
        }
        $this->setData('customer', Mage::getModel('customer/customer')->load($this->getCustomerIdFromRequest()));
        return $this->getData('customer');
    }
}

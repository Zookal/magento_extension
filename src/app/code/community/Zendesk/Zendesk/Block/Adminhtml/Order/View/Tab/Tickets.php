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
class Zendesk_Zendesk_Block_Adminhtml_Order_View_Tab_Tickets extends Mage_Adminhtml_Block_Widget_Container
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

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
        if (Mage::registry('current_order')) {
            return Mage::registry('current_order');
        }
        if (Mage::registry('order')) {
            return Mage::registry('order');
        }
        Mage::throwException(Mage::helper('sales')->__('Cannot get order instance'));
    }

    public function getTabClass()
    {
        return 'ajax';
    }

    /**
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('*/zendesk/salesOrderViewTicketGrid', array('order_id' => $this->getOrder()->getId() ));
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->helper('AdvancedStock')->__('Zendesk Tickets');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->helper('AdvancedStock')->__('Zendesk Tickets');
    }

    /**
     * Can show tab in tabs
     * @return boolean
     */
    public function canShowTab()
    {
        return Mage::getStoreConfigFlag('zendesk/features/show_on_order');
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}

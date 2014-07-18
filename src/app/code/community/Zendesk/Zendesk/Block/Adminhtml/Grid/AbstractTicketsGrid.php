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
abstract class Zendesk_Zendesk_Block_Adminhtml_Grid_AbstractTicketsGrid extends Mage_Adminhtml_Block_Widget_Grid

{
    public function __construct()
    {
        parent::__construct();
        $this->setSaveParametersInSession(true);
        $this->setFilterVisibility(false);
        $this->setUseAjax(true);
        $this->setRowClickCallback(false);
    }

    /**
     * @return array
     */
    abstract protected function _getTickets();

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = new Varien_Data_Collection();
        $tickets    = $this->_getTickets();
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
            'renderer' => 'zendesk/adminhtml_widget_grid_column_renderer_ticketGroup',
            'filter'   => false,
            'sortable' => false
        ));

        $this->addColumn('ticket_detail_assignee', array(
            'header'   => Mage::helper('zendesk')->__('Assignee'),
            'index'    => 'ticket_detail_assignee',
            'renderer' => 'zendesk/adminhtml_widget_grid_column_renderer_ticketAssignee',
            'filter'   => false,
            'sortable' => false
        ));

        $this->addColumn('action',
            array(
                'header'   => Mage::helper('catalog')->__('Action'),
                'width'    => '50px',
                'type'     => 'action',
                'renderer' => 'zendesk/adminhtml_widget_grid_column_renderer_viewTicket',
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
}

<?php

    class Appollo_Configurable_Model_Mysql4_Configurable_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
    {
        public function _construct()
        {      
            $this->_init('appollo_configurable/configurable');
        }
    }
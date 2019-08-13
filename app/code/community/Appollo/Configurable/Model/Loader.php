<?php

    class Appollo_Configurable_Model_Loader {

        public function toOptionArray() {             
            return array(
                array('value' => 'default', 'label' => 'Default Loader'),
                array('value' => 'cart', 'label' => 'Shopping Cart'),
                array('value' => 'coffee_cup', 'label' => 'Coffee Cup'),
                array('value' => 'gears', 'label' => 'Gears')
            );
        }

    }
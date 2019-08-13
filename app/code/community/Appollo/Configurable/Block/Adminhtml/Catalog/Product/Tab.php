<?php
 
class Appollo_Configurable_Block_Adminhtml_Catalog_Product_Tab
extends Mage_Adminhtml_Block_Template
implements Mage_Adminhtml_Block_Widget_Tab_Interface {
 
    /**
     * Set the template for the block
     *
     */
    public function _construct()
    {
        parent::_construct();
        
        $product_id = Mage::registry('current_product')->getId();
        $configurable_model = Mage::getModel('appollo_configurable/configurable')->getCollection()->addFieldToFilter('product_id', $product_id)->getFirstItem();
        
        if( $configurable_model->getId() )
        {
            $data = array(
                'enabled' => $configurable_model->getEnabled(),
                'update_name' => $configurable_model->getUpdateName(),
                'update_review' => $configurable_model->getUpdateReview(),
                'update_description' => $configurable_model->getUpdateDescription(),
                'update_images' => $configurable_model->getUpdateImages(),
                'update_additional_info' => $configurable_model->getUpdateAdditionalInfo()
            );
            
            
        }
        else
            $data = array(
                'enabled' => 1,
                'update_name' => 1,
                'update_review' => 1,
                'update_description' => 1,
                'update_images' => 1,
                'update_additional_info' => 1
            );
        
        $this->setData('acp_data', $data);
        
        $this->setTemplate('appollo_configurable/product_tab.phtml');
    }
     
    /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Appollo Configurable');
    }
     
    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Click here to view ACP Options');
    }
     
    /**
     * Determines whether to display the tab
     * Add logic here to decide whether you want the tab to display
     *
     * @return bool
     */
    public function canShowTab()
    {
        if( Mage::registry('current_product')->isConfigurable() )
            return true;
        else 
            return false;
    }
     
    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
 
     
    /**
     * AJAX TAB's
     * If you want to use an AJAX tab, uncomment the following functions
     * Please note that you will need to setup a controller to recieve
     * the tab content request
     *
     */
    /**
     * Retrieve the class name of the tab
     * Return 'ajax' here if you want the tab to be loaded via Ajax
     *
     * return string
     */
#   public function getTabClass()
#   {
#       return 'my-custom-tab';
#   }
 
    /**
     * Determine whether to generate content on load or via AJAX
     * If true, the tab's content won't be loaded until the tab is clicked
     * You will need to setup a controller to handle the tab request
     *
     * @return bool
     */
#   public function getSkipGenerateContent()
#   {
#       return false;
#   }
 
    /**
     * Retrieve the URL used to load the tab content
     * Return the URL here used to load the content by Ajax
     * see self::getSkipGenerateContent & self::getTabClass
     *
     * @return string
     */
#   public function getTabUrl()
#   {
#       return null;
#   }
 
}
<?php
  
class Appollo_Configurable_Model_Observer
{
    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;
 
    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveProductTabData(Varien_Event_Observer $observer)
    {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;
             
            $product = $observer->getEvent()->getProduct();
         
            try {
                /**
                 * Perform any actions you want here
                 *
                 */
                if( $product->isConfigurable() )
                {
                    $acp_data = array(
                        'enabled' => $this->_getRequest()->getPost('enabled'),
                        'update_name' => $this->_getRequest()->getPost('update_name'),
                        'update_review' => $this->_getRequest()->getPost('update_review'),
                        'update_description' => $this->_getRequest()->getPost('update_description'),
                        'update_images' => $this->_getRequest()->getPost('update_images'),
                        'update_additional_info' => $this->_getRequest()->getPost('update_additional_info'),
                        'product_id' => $product->getId()
                    );

                    $acp_model = Mage::getModel('appollo_configurable/configurable');

                    $acp_exists = $acp_model->getCollection()->addFieldToFilter('product_id', $product->getId())->getFirstItem();

                    if( $id = $acp_exists->getId() )
                    {
                        //entry exists                    
                        $acp_model->load($id)->addData( $acp_data );                    
                        $acp_model->setId($id)->save();
                    }
                    else
                    {
                        $acp_model->setData( $acp_data );
                        $acp_model->save();
                    }
                }
                                                                    
                /**
                 * Uncomment the line below to save the product
                 *
                 */
                //$product->save();
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }
      
    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }
     
    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
}
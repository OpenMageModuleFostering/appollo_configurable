<?php

class Appollo_Configurable_ConfigurableController extends Mage_Core_Controller_Front_Action 
{

    public function associatedAction() {
        $model = Mage::getModel('catalog/product');

        $values = $_POST['values'];
        $attributes = $_POST['attributes'];

        $configurable = $this->getRequest()->getParam('configurable');
        $compare = array();

        $easylightbox = 'enabled';

        if ($lightbox = Mage::getStoreConfig('easy_lightbox/general')) {
            if ($lightbox['enabled'] == 1) {
                $easy_lightbox = array();
                if (strstr($lightbox['mainImageSize'], '_')) {
                    $easy_lightbox['mainImage'] = explode('_', $lightbox['mainImageSize'], 2);
                } else {
                    $easy_lightbox['mainImage'] = array(265, 265);
                }
                if (strstr($lightbox['popupImageSize'], '_')) {
                    $easy_lightbox['popupImage'] = explode('_', $lightbox['popupImageSize'], 2);
                } else {
                    $easy_lightbox['popupImage'] = array(null, null);
                }
                if (strstr($lightbox['additionalImageSize'], '_')) {
                    $easy_lightbox['additionalImage'] = explode('_', $lightbox['additionalImageSize'], 2);
                } else {
                    $easy_lightbox['additionalImage'] = array(60, 60);
                }
            }
            else
                $easylightbox = 'disabled';
        }
        else
            $easylightbox = 'disabled';

        if ($easylightbox == 'disabled') {
            $easy_lightbox = array(
                'mainImage' => array(265, 265),
                'popupImage' => array(null, null),
                'additionalImage' => array(56, 56)
            );
        }

        $n = 0;

        foreach ($attributes['attributes'] as $attribute):
            $key = $attribute['code'];
            foreach ($attribute['options'] as $option):

                if ($values[$n] == $option['id']) {
                    $compare[] = array($key => $option['label']);
                }
            endforeach;

            $n++;
        endforeach;

        $children_ids = Mage::getModel('catalog/product_type_configurable')->getChildrenIds($configurable);

        foreach ($children_ids as $child):
            foreach ($child as $item):

                $pass = true;
                $product = Mage::getModel('catalog/product')->load($item);

                foreach ($compare as $comp):
                    foreach ($comp as $key => $val):
                        $attr = $product->getAttributeText($key);

                        if ($attr != $val) {
                            $pass = false;
                        }

                    endforeach;
                endforeach;

                if ($pass) {
                    $name = $product->getName();
                    $prod = $item;
                    break;
                }

            endforeach;
        endforeach;

        $gallery = $product->getMediaGalleryImages();
        $images = array();

        foreach ($gallery as $image):
            $images[] = array(
                'thumbnail' => (string) Mage::helper('catalog/image')->init($product, 'thumbnail', $image->getFile())->resize($easy_lightbox['additionalImage'][0], $easy_lightbox['additionalImage'][1]),
                'large' => (string) Mage::helper('catalog/image')->init($product, 'thumbnail', $image->getFile())->resize($easy_lightbox['popupImage'][0], $easy_lightbox['popupImage'][1])
            );
        endforeach;

        if ($product->getIsInStock())
            $stock = 'in';
        else {
            $stock = 'out';
        }

        $product_data = array(
            'name' => str_replace('-', ' ', $name),
            'description' => $product->getDescription(),
            'short_description' => $product->getShortDescription(),
            'price' => substr($product->getPrice(), 0, -2),
            'images' => $images,
            'base_image_large' => (string) Mage::helper('catalog/image')->init($product, 'small_image')->resize($easy_lightbox['popupImage'][0], $easy_lightbox['popupImage'][1]),
            'base_image_small' => (string) Mage::helper('catalog/image')->init($product, 'small_image')->resize($easy_lightbox['mainImage'][0], $easy_lightbox['mainImage'][1]),
            'thumbnail' => $product->getThumbnail(),
            'stock' => $stock,
            'easy_lightbox' => $easylightbox
        );

        $data = array();

        $attributes = $product->getAttributes();

        foreach ($attributes as $attribute) {
            if ($attribute->getIsVisibleOnFront() && $attribute->getIsUserDefined() && !in_array($attribute->getAttributeCode(), $excludeAttr)) {
                //if ($attribute->getIsVisibleOnFront() && !in_array($attribute->getAttributeCode(), $excludeAttr)) {
                $value = $attribute->getFrontend()->getValue($product);

                if (!$product->hasData($attribute->getAttributeCode())) {
                    $value = Mage::helper('catalog')->__('N/A');
                } elseif ((string) $value == '') {
                    $value = Mage::helper('catalog')->__('No');
                } elseif ($attribute->getFrontendInput() == 'price' && is_string($value)) {
                    $value = Mage::app()->getStore()->convertPrice($value, true);
                }

                if (is_string($value) && strlen($value)) {
                    $data[$attribute->getAttributeCode()] = array(
                        'label' => $attribute->getStoreLabel(),
                        'value' => $value,
                        'code' => $attribute->getAttributeCode()
                    );
                }
            }
        }

        $data_table = '<colgroup><col width="25%"><col></colgroup><tbody>';

        $n = 1;
        foreach ($data as $row):
            if (($n % 2) == 0)
                $tr_class = 'even '; else
                $tr_class = 'odd ';

            if ($n == 1)
                $tr_class .= 'first';

            if ($row['value'] != 'Nein') {
                $data_table .= '<tr class="' . $tr_class . '"><th class="label">' . $row['label'] . '</th><td class="data last">' . $row['value'] . '</td></tr>';
                $n++;
            }

        endforeach;

        $data_table .= '</tbody>';

        $product_data['additional_info'] = $data_table;

        echo json_encode($product_data);
    }

}
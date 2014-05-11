<?php

class Hackathon_AttributeConfigurator_Model_Sync_Import extends Mage_Core_Model_Abstract
{

    /** @var Hackathon_AttributeConfigurator_Helper_Data $_helper */
    protected $_helper;

   
    /**
     * Attribute Data
     * @var array
     */
    protected $_attributeData = array();

    /**
     * Attribute-Set Data
     * @var array
     */
    protected $_setData = array();

    protected $_groupData = array();


 public function _construct()
    {
        $this->_helper = Mage::helper('hackathon_attributeconfigurator/data');

        $this->bibiBlocksberg();
    }

    /**
     * Sync Import Method coordinates the migration process from
     * XML File Data into the Magento Database
     *
     * return bool
     */

    public function import()
    {
        $_config = Mage::getConfig();


        // 1. Import/Delete Attribute Sets
        $attributesets = $_config->getNode('attributesetslist');


        // 2. Import/Delete Attributes
        $attributes = $_config->getNode('attributeslist');

        if ($this->_validate($attributesets, $attributes)) {

            // 3. Connect Attributes with Attribute Sets using Attribute Groups
        }

    }


    public function prepareAttributeSet($xml)
    {
        $this->_setData = json_decode(json_encode($xml->attributesets), true);
        return $this;
    }

    protected function _getAttributeSetsFromXml($attributesets)
    {
        $returnarray = array();
        foreach ($attributesets->children() as $attributeset) {
            $returnarray[] = (string) $attributeset['name'];
        }

        return $returnarray;
    }



    /**
     * @param $xml
     * @return $this
     */
    public function prepareAttributes($xml)
    {   
     $this->_attributeData = json_decode(json_encode($xml->attributes), true);
        return $this;
        }
	/** @TODO: RICO schön machen und weitermachen :D **/
    protected function _validate($attributesets, $attributes)
    {

        $attributesets = $attributesets;
        $lo_attributesets = $this->_getAttributeSetsFromXml($attributesets);
        $attributes    = $attributes;

        foreach ($attributes->children() as $attribute) {
            foreach ($attribute->attributesets->children() as $attributeset) {
                //echo $attribute["code"] . " gehört zu " . $attributeset["name"] . " <br />";
            }
        }



                if(!in_array($attributeset["name"], $lo_attributesets)){
                    throw new Mage_Adminhtml_Exception("Attributeset '".$attributeset["name"]."' referenced by '".$attribute["code"]."' is not listed in the attributesetslist element");
                }

        foreach ($attributesets->children() as $attributeset) {
            //echo $attributeset['name'] . "<br />";
        }

        return false;
}
   public function prepareAttributeGroups($xml)
    {
        $this->_groupData = json_decode(json_encode($xml->attributegroups), true);
        return $this;    }

    protected function bibiBlocksberg()
    {
        Mage::getConfig()->loadFile($this->_helper->getImportFilename());
    }
}
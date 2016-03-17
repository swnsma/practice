<?php
class ISM_NewstoreMembers_Model_Import extends Mage_Core_Model_Abstract
{
    protected $_path;
    protected $_fileName;

    public function upload()
    {
        if (isset($_FILES['import_file']['name']) && $_FILES['import_file']['name'] != '') {
            $uploader = new Varien_File_Uploader('import_file');
            $uploader->setAllowedExtensions(array('csv', 'xml'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            $this->_path = Mage::getBaseDir('media').DS;
            $this->_fileName = $_FILES['import_file']['name'];
            $uploader->save($this->_path, $this->_fileName);
        }
        return $this;
    }

    public function import()
    {
        $file = $this->_path.$this->_fileName;
        if (!is_file($file)) {
           Mage::throwException(Mage::helper('ism_newstore_members')->__('Invalid file!'));
        }
        $csv = new Varien_File_Csv();
        $data = $csv->getData($file);

        unset($data[0]);

        $skuCodes = array_column($data, 0);
        $prices = array_column($data, 1);

        $collection = Mage::getModel('catalog/product')->getCollection()
                   ->addAttributeToSelect('*')
                   ->addAttributeToFilter('sku', array('in' => $skuCodes));

        $prices = array_combine($skuCodes, $prices);

        foreach ($collection as $item) {
            try {
                $item->setIsmNewstoremembersPrice($prices[$item->getSku()])->save();
            } catch (Exception $e) {
                Mage::log('Error with product: '.$item->getSku());
            }
        }
        unlink($file);
        return $this;
    }

}
<?php
namespace STCAdmin\CSV;

class ImageUrlFile extends CsvFile {

    public function __construct()
    {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '05_imageUrls.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'Is Primary', 'Filepath');
        $this->imageUrl = $IMAGEURLPATH;
    }

    public function getRowArray($product)
    {
        $sku = $product->details['sku']->text;
        $imageInfo = getImageIdsForSKU($sku);
        $newRows = array();
        foreach ($imageInfo as $image) {
            if ($image['is_primary'] == true) {
                $isPrimary = 'TRUE';
            } else {
                $isPrimary = 'FALSE';
            }
            $imageUrl = $this->imageUrl . $image['id'];
            $newRow = array($sku, $isPrimary, $imageUrl);
            $newRows[] = $newRow;
        }

        return $newRows;
    }

    public function getRowsArray()
    {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $product = $_SESSION['new_product'];
        $rowsArray = array();
        if ($product->details['var_type']->value == true) {
            foreach ($product->variations as $variation) {
                foreach ($this->getRowArray($variation) as $newRow) {
                    $rowsArray[] = $newRow;
                }
            }
        } else {
            foreach ($this->getRowArray($product) as $newRow) {
                $rowsArray[] = $newRow;
            }
        }

        return $rowsArray;
    }

    public function write()
    {
        $rowsArray = $this->getRowsArray();
        foreach ($rowsArray as $row) {
            $this->addline($row);
        }
    }
}

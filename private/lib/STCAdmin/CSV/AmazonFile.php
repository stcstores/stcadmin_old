<?php
namespace STCAdmin\CSV;

class AmazonFile extends CsvFile {

    public function __construct()
    {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '07_channel_amazon.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array(
            'SKU',
            'Title',
            'Description',
            'AmazonBullet1',
            'AmazonBullet2',
            'AmazonBullet3',
            'AmazonBullet4',
            'AmazonBullet5'
        );
    }

    public function getRowArray($product)
    {
        $newRow = array();
        $newRow[] = $product->details['sku']->text;
        $newRow[] = $product->details['am_title']->text;
        $newRow[] = $product->details['am_description']->text;
        $newRow[] = $product->details['am_bullet_1']->text;
        $newRow[] = $product->details['am_bullet_2']->text;
        $newRow[] = $product->details['am_bullet_3']->text;
        $newRow[] = $product->details['am_bullet_4']->text;
        $newRow[] = $product->details['am_bullet_5']->text;
        return $newRow;
    }

    public function getRowsArray()
    {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $product = $_SESSION['new_product'];

        $rowsArray = array();

        $rowsArray[] = $this->getRowArray($product);

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

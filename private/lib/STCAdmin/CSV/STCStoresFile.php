<?php
namespace STCAdmin\CSV;

class STCStoresFile extends CsvFile {

    public function __construct()
    {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '08_channel_stcstores.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'Title', 'Description');
    }

    public function getRowArray($product)
    {
        $newRow = array();
        $newRow[] = $product->details['sku']->text;
        $newRow[] = $product->details['item_title']->text;
        $newRow[] = $product->toHTML($product->details['short_description']->text);
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

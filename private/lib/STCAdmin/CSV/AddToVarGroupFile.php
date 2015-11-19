<?php
namespace STCAdmin\CSV;

class AddToVarGroupFile extends CsvFile {
    public function __construct()
    {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '03_add_to_var_group.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'VariationSKU');
    }

    public function write()
    {
        $product = $_SESSION['new_product'];
        $productSKU = $product->details['sku']->text;
        foreach ($product->variations as $variation) {
            $varSKU = $variation->details['sku']->text;
            $this->addline(array($productSKU, $varSKU));
        }
    }
}

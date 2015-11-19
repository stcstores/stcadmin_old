<?php
namespace STCAdmin\CSV;

class NewVarGroupFile extends CsvFile {
    public function __construct()
    {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '01_new_var_group.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('VariationSKU', 'VariationGroupName');
    }

    public function write()
    {
        $product = $_SESSION['new_product'];
        $title = $product->getLinnTitle();
        $sku = $product->details['sku']->text;
        $rowArray = array($sku, $title);
        $this->addline($rowArray);
    }
}

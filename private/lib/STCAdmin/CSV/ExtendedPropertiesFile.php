<?php
namespace STCAdmin\CSV;

class ExtendedPropertiesFile extends CsvFile {
    public function __construct()
    {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '04_extended_properties.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'PropertyType', 'PropertyName', 'PropertyValue');
    }

    public function getRowArray($product)
    {
        $rowsArray = array();
        $sku = $product->details['sku']->text;
        foreach (getExtendedProperties() as $extendedProp) {
            if ($product->details[$extendedProp['field_name']]->text != '') {
                $newRow = array();
                $newRow[] = $sku;
                if ($extendedProp['field_name'] == 'int_shipping') {
                    $newRow[] = 'Specification';
                } else {
                    $newRow[] = 'Attribute';
                }
                $newRow[] = $extendedProp['field_title'];
                $newRow[] = $product->details[$extendedProp['field_name']]->text;
                $rowsArray[] = $newRow;
            }
        }
        return $rowsArray;
    }

    public function getRowsArray()
    {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $product = $_SESSION['new_product'];
        $rowsArray = $this->getRowArray($product);

        if ($product->details['var_type']->value == true) {
            foreach ($product->variations as $variation) {
                foreach ($this->getRowArray($variation) as $newRow) {
                    $rowsArray[] = $newRow;
                }
            }
        }
        //print_r($rowsArray);
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

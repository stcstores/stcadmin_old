<?php
namespace STCAdmin\CSV;

class BasicInfoFile extends CsvFile {
    public function __construct()
    {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '02_basic_info.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array(
            'SKU',
            'Title',
            'PurchasePrice',
            'RetailPrice',
            'Weight',
            'BarcodeNumber',
            'Category',
            'ShortDescription',
            'PackagingGroup',
            'PostalService',
            'DimHeight',
            'DimWidth',
            'DimDepth'
        );
    }

    public function write()
    {
        $rowsArray = $this->getRowsArray();
        foreach ($rowsArray as $row) {
            $currentRow = array();
            foreach ($this->header as $column) {
                $currentRow[] = $row[$column];
            }
            $this->addline($currentRow);
        }
    }

    public function getRowsArray()
    {
        $product = $_SESSION['new_product'];
        $rowsArray = array();
        if (isset($_SESSION['new_product'])) {
            if ($product->details['var_type']->value == true) {
                foreach ($product->variations as $variation) {
                    $varArray = array(
                        'SKU' => $variation->details['sku']->text,
                        'Title' => $variation->getLinnTitle(),
                        'PurchasePrice' => $variation->details['purchase_price']->text,
                        'RetailPrice' => $variation->details['retail_price']->text,
                        'Weight' => $variation->details['weight']->text,
                        'BarcodeNumber' => $variation->details['barcode']->text,
                        'Category' => $product->details['department']->text,
                        'ShortDescription' => $product->details['short_description']->text,
                        'PackagingGroup' => 'Packet 2nd RM',
                        'PostalService' => $product->details['shipping_method']->text,
                        'DimHeight' => $variation->details['height']->text,
                        'DimWidth' => $variation->details['width']->text,
                        'DimDepth' => $variation->details['depth']->text,
                    );
                    $rowsArray[] = $varArray;
                }
            } else {
                $rowsArray[] = array(
                    'SKU' => $product->details['sku']->text,
                    'Title' => $product->getLinnTitle(),
                    'PurchasePrice' => $product->details['purchase_price']->text,
                    'RetailPrice' => $product->details['retail_price']->text,
                    'Weight' => $product->details['weight']->text,
                    'BarcodeNumber' => $product->details['barcode']->text,
                    'Category' => $product->details['department']->text,
                    'ShortDescription' => $product->details['short_description']->text,
                    'PackagingGroup' => 'Packet 2nd RM',
                    'PostalService' => $product->details['shipping_method']->text,
                    'DimHeight' => $product->details['height']->text,
                    'DimWidth' => $product->details['width']->text,
                    'DimDepth' => $product->details['depth']->text,
                );
            }
            return $rowsArray;

        } else {
            echo "No Product";
        }
    }
}

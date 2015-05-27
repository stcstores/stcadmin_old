<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');




class CsvFile {
    function __construct($path, $filename, $header){
        $this ->path = $path;
        $this->filename = $filename;
        $this->filepath = $path . '/' . $filename;
        $this->header = $header;
        
    }
    
    function createIfNotExists() {
        if (!file_exists($this->path)){
            mkdir($this->path);
            
        }
        if (!file_exists($this->filepath)) {
            $file = fopen($this->filepath, 'w');
            fputcsv($file, $this->header);
            fclose($file);
        }
    }
    
    function openWrite() {
        $file = fopen($this->filepath, 'w');
        return $file;
    }
    
    function openRead() {
        $file = fopen($this->filepath, 'r');
        return $file;
    }
    
    function addline($newline) {
        $this->createIfNotExists();
        $file = $this->openRead();
        
        $existingLines = array();
        while (($line = fgetcsv($file)) !== false ) {
            $existingLines[] = $line;
        }
        fclose($file);
        $file = $this->openWrite();
        //fputcsv($file, $this->header);
        foreach ($existingLines as $line) {
            fputcsv($file, $line);
        }
        fputcsv($file, $newline);
        fclose($file);
    }
}




class NewVarGroupFile extends CsvFile {
    function __construct() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '01_new_var_group.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('VariationSKU', 'VariationGroupName');
    }
    
    function write() {
        $product = $_SESSION['new_product'];
        $title = $product->details['item_title']->text;
        $sku = $product->details['sku']->text;
        $rowArray = array($sku, $title);
        $this->addline($rowArray);
    }
}




class BasicInfoFile extends CsvFile {
    function __construct() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '02_basic_info.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'Title', 'PurchasePrice', 'RetailPrice', 'Weight', 'BarcodeNumber', 'Category', 'ShortDescription', 'PackagingGroup', 'PostalService', 'DimHeight', 'DimWidth', 'DimDepth');
    }
    
    function write() {
        $rowsArray = $this->getRowsArray();
        foreach ($rowsArray as $row) {
            $currentRow = array();
            foreach ($this->header as $column) {
                $currentRow[] = $row[$column];
            }
            $this->addline($currentRow);
        }
    }
    
    function getRowsArray() {
        $product = $_SESSION['new_product'];
        $rowsArray = array();
        if (isset($_SESSION['new_product'])) {
            if ($product->details['var_type']->value == true) {
                foreach ($product->variations as $variation) {
                    $varArray = array(
                        'SKU' => $variation->details['sku']->text,
                        'Title' => $variation->details['var_name']->text,
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
                    'Title' => $product->details['item_title']->text,
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




class AddToVarGroupFile extends CsvFile {
    function __construct() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '03_add_to_var_group.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'VariationSKU');
    }
    
    function write() {
        $product = $_SESSION['new_product'];
        $productSKU = $product->details['sku']->text;
        foreach ($product->variations as $variation) {
            $varSKU = $variation->details['sku']->text;
            $this->addline(array($productSKU, $varSKU));
        }
    }
}



class ExtendedPropertiesFile extends CsvFile {
    function __construct() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '04_extended_properties.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'PropertyType', 'PropertyName', 'PropertyValue');
    }
    
    function getRowArray($product) {
        $rowsArray = array();
        $sku = $product->details['sku']->text;
        foreach (getExtendedProperties() as $extendedProp) {
            if ($product->details[$extendedProp['field_name']]->text != '') {
                $newRow = array();
                $newRow[] = $sku;
                if ($extendedProp['field_name'] == 'int_shipping'){
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
    
    function getRowsArray() {
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
    
    function write() {
        $rowsArray = $this->getRowsArray();
        foreach ($rowsArray as $row) {
            $this->addline($row);
        }
    }
}



class ImageUrlFile extends CsvFile {
    function __construct() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '05_imageUrls.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'Is Primary', 'Filepath');
        $this->imageUrl = $IMAGEURLPATH;
    }
    
    function getRowArray($product) {
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
    
    function getRowsArray() {
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
    
    function write() {
        $rowsArray = $this->getRowsArray();
        foreach ($rowsArray as $row) {
            $this->addline($row);
        }
    }
}




class EbayFile extends CsvFile {
    function __construct() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '06_channel_ebay.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'Title', 'Description');
    }
    
    function getRowArray($product) {
        $newRow = array();
        $newRow[] = $product->details['sku']->text;
        $newRow[] = $product->details['ebay_title']->text;
        $newRow[] = $product->details['ebay_description']->text;
        return $newRow;
    }
    
    function getRowsArray() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $product = $_SESSION['new_product'];
        
        $rowsArray = array();
        
        $rowsArray[] = $this->getRowArray($product);
        
        return $rowsArray;
    }
    
    function write() {
        $rowsArray = $this->getRowsArray();
        foreach ($rowsArray as $row) {
            $this->addline($row);
        }
    }
}




class AmazonFile extends CsvFile {
    function __construct() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '07_channel_amazon.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'Title', 'Description', 'AmazonBullet1', 'AmazonBullet2', 'AmazonBullet3', 'AmazonBullet4', 'AmazonBullet5');
    }
    
    function getRowArray($product) {
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
    
    function getRowsArray() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $product = $_SESSION['new_product'];
        
        $rowsArray = array();
        
        $rowsArray[] = $this->getRowArray($product);
        
        return $rowsArray;
    }
    
    function write() {
        $rowsArray = $this->getRowsArray();
        foreach ($rowsArray as $row) {
            $this->addline($row);
        }
    }
}




class StcStoresFile extends CsvFile {
    function __construct() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $this->path = $CSVFILEPATH;
        $this->filename = '08_channel_stcstores.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = array('SKU', 'Title', 'Description');
    }
    
    function getRowArray($product) {
        $newRow = array();
        $newRow[] = $product->details['sku']->text;
        $newRow[] = $product->details['shopify_title']->text;
        $newRow[] = $product->details['shopify_description']->text;
        return $newRow;
    }
    
    function getRowsArray() {
        include(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
        include($CONFIG['constants']);
        $product = $_SESSION['new_product'];
        
        $rowsArray = array();
        
        $rowsArray[] = $this->getRowArray($product);
        
        return $rowsArray;
    }
    
    function write() {
        $rowsArray = $this->getRowsArray();
        foreach ($rowsArray as $row) {
            $this->addline($row);
        }
    }
}
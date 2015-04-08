<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    
    echo $CSVFILEPATH;
    
    if (isset($_SESSION['new_product'])) {
        $varSet = $_SESSION['new_product']->details['var_type']->value;
        
        if ($varSet) {
            $newVarGroupFile = new NewVarGroupFile();
            
            $newVarGroupFile->write();
        }
        
        
        $basicInfoFile = new BasicInfoFile();
        $basicInfoFile->write();
        
        if ($varSet) {
            $addToVarGroupFile = new AddToVarGroupFile();
            $addToVarGroupFile->write();
        }
        
        $imageUrlFile = new ImageUrlFile();
        $imageUrlFile->write();
        
        $extendedPropertiesFile = new ExtendedPropertiesFile();
        $extendedPropertiesFile->write();
        
        $ebayChannelFile = new EbayFile();
        $ebayChannelFile->write();
        
        $stcStoresFile = new StcStoresFile();
        $stcStoresFile->write();
        
        $amazonFile = new AmazonFile();
        $amazonFile->write();
        
        
    } else {
        echo "No Product";
    }

    
    
    echo "complete";
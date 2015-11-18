<?php
namespace LSPHP;

class ImageTools {
    public function imageToBinary($image)
    {
        $fp = fopen($image, 'r');
        $imageData = fread($fp, filesize($image));
        $imageData = addslashes($imageData);
        fclose($fp);
        return $imageData;
    }

    public function hasImageExtenstion($filename)
    {
        $imageExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), $imageExtensions)) {
            return true;
        } else {
            return false;
        }
    }
}

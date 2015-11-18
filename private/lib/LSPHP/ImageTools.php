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
}

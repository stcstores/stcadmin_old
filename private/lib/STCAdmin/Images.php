<?php
namespace STCAdmin;

class Images {
    public function __construct()
    {
        $this -> images = array();
        $this -> primary = 0;
    }

    public function setPrimary($guid)
    {
        $i = 0;
        foreach ($this->images as $image) {
            if ($image->guid == $guid) {
                $newPrimeId = $i;
            }
            $i++;
        }
        $newPrimeImage = $this->images[$newPrimeId];
        unset($this->images[$newPrimeId]);
        array_unshift($this->images, $newPrimeImage);
    }

    public function addImage($guid, $thumbPath, $fullPath)
    {
        $this -> images[] = new Image($guid, $thumbPath, $fullPath);
    }

    public function removeImage($guid)
    {
        $i = 0;
        foreach ($this->images as $image) {
            if ($image->guid == $guid) {
                $idToRemove = $i;
            }
            $i++;
        }

        array_splice($this->images, $idToRemove, 1);
    }
}

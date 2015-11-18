<?php
namespace STCAdmin;

class Image {
    public function __construct($guid, $thumbPath, $fullPath)
    {
        $this -> guid = $guid;
        $this -> thumbPath = $thumbPath;
        $this -> fullPath = $fullPath;
    }
}

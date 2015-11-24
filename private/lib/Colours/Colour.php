<?php
namespace Colours;

class Colour {
    public function construct()
    {
        $this->shades = array();
    }

    public function addShade($shade, $number)
    {
        $this->shades[$number] = $shade;
    }
}

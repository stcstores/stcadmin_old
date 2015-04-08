<?php

class ColourScheme {
    function __construct($colourFile) {
        $this -> colours = array();
        $colour = 1;
        $shade = 1;
        $handle = fopen($colourFile, "r");
        if ($handle) {
            $colourNumber = 1;
            $shadeNumber = 1;
            $this->colours[(string)$colourNumber] = new Colour();
            while (($line = fgets($handle)) !== false) {
                if (trim($line) == "") {
                    $colourNumber ++;
                    $shadeNumber = 1;
                    $this->colours[(string)$colourNumber] = new Colour();
                } else {
                    $this->colours[(string)$colourNumber] -> addShade($line, (string)$shadeNumber);
                    $shadeNumber ++;
                }
            }
        
            fclose($handle);
        } else {
            echo "file not found";
        } 
    }
    
    function echoColour($colour, $shade) {
        echo $this->colours[(string)$colour] -> shades[(string)$shade];
    }
    
    function showScheme() {
        $height = 50;
        $width = 100;
        echo "<div id=ColourScheme>";
        foreach ($this->colours as $colNum => $col) {
            echo "<div id=colour_{$colNum} style='margin:10px;border:solid 3px black;background:white;padding:5px;'>";
            echo "{$colNum} <br />";
            foreach ($col->shades as $shadeNum => $shade){
                echo "<div id=colour_{$colNum}_shade{$shadeNum} style='width:{$width}px;height:{$height}px;background:{$shade};display:inline-block;margin-right:5px;'>";
                echo "{$shade}<br />{$shadeNum}";
                echo "</div>";
            }
            echo "</div>";
        }
        
        echo "</div>";
    }
}

class Colour {
    function construct(){
        $this->shades = array();
    }
    
    function addShade($shade, $number) {
        $this->shades[$number] = $shade;
    }
}


?>
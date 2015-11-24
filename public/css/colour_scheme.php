<?php

class ColourScheme {
    public function __construct($colourFile)
    {
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

    public function echoColour($colour, $shade)
    {
        echo $this->colours[(string)$colour] -> shades[(string)$shade];
    }

    public function showScheme()
    {
        $height = 50;
        $width = 100;
        echo "<div id=ColourScheme>";
        foreach ($this->colours as $colourNumber => $colour) {
            echo "\t<div id=colour_";
            echo $colourNumber;
            echo " style='margin:10px;border:solid 3px black;background:white;padding:5px;'>";
            echo "{$colourNumber} <br />";
            foreach ($colour->shades as $shadeNumber => $shade) {
                echo "\t\t<div style='display:inline-block; overflow:hidden;'>";
                echo "{$shadeNumber}: {$shade}<br />";
                echo "\t\t\t<div id=colour_";
                echo $colourNumber . "_shade" . $shadeNumber;
                echo " style='width:" . $width . "px;";
                echo " height:" . $height . "px;";
                echo "background: " . $shade . "; ";
                echo "display:inline-block;margin-right:5px;'>";
                echo "\t\t\t</div>";
                echo "\t\t</div>";
            }
            echo "\t</div>";
        }
        echo "</div>";
    }
}

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

?>

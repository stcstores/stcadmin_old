<?php



function getColour($colour, $shade) {
    $COLOURS = array();
    $COLOURS[] = array();
    $COLOURS[] = array();
    $COLOURS[] = array();
    $COLOURS[] = array();
    
    $COLOURS[0] = array('#73D464', '#4DBC3C', '#2EAB1A', '#1D8A0C', '#0E6900');
    
    $COLOURS[1] = array('#FFB178', '#E38A48', '#CF6A20', '#A74F0E', '#7F3600');
    
    $COLOURS[2]= array('#C95EA1', '#B13984', '#A2196F', '#820B56', '#63003E');
    
    $COLOURS[3]= array('#F9757F', '#DE4752', '#CA1F2B', '#A20E19', '#7C0009');
    
    echo $COLOURS[$colour - 1][$shade - 1];
}

#2EAB1A
#73D464
#4DBC3C
#1D8A0C
#0E6900

#CF6A20
#FFB178
#E38A48
#A74F0E
#7F3600

#A2196F
#C95EA1
#B13984
#820B56
#63003E

#CA1F2B
#F9757F
#DE4752
#A20E19
#7C0009

?>
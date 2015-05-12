<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin($admin_required=true);
require_once($CONFIG['header']);

function monthNumberToName($month) {
    $months = array("01" => 'January', "02" => 'February', "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September", "10" => "October", "11" => "November", "12" => "December");
    return $months[$month];
}

function addOrdinalSuffix($day) {
    if (substr($day, -2, 2) == 11) {
        $suffix = 'th';
    } elseif (substr($day, -2, 2) == 12) {
        $suffix = 'th';
    } elseif (substr($day, -2, 2) == 13) {
        $suffix = 'th';
    } elseif (substr($day, -1, 1) == 1) {
        $suffix = 'st';
    } elseif (substr($day, -1, 1) == 2) {
        $suffix = 'nd';
    } elseif (substr($day, -1, 1) == 3) {
        $suffix = 'rd';
    } else {
        $suffix = 'th';
    }
    
    if (substr($day, 0, 1) == 0) {
        $day = substr($day, 1, 50);
    }
    
    return $day . "<sup>{$suffix}</sup>";
}

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);



foreach (scandir($OLDCSVFILEPATH) as $filename) {
    if (!in_array($filename, array("..", ".", "", ".gitignore"))){
        $folders[] = $filename;
    }
}

$archive = array();

foreach ($folders as $folder) {
    $year = substr($folder, 0, 4);
    $month = substr($folder, 5, 2);
    $day = substr($folder, 8, 2);
    $time = substr($folder, 11);
    
    if (!array_key_exists($year, $archive)) {
        $archive[$year] = array();
    }
    if (!array_key_exists($month, $archive[$year])) {
        $archive[$year][$month] = array();
    }
    if (!array_key_exists($day, $archive[$year][$month])) {
        $archive[$year][$month][$day] = array();
    }
    if (!array_key_exists($time, $archive[$year][$month][$day])) {
        $archive[$year][$month][$day][$time] = array();
    }
    $archive[$year][$month][$day][$time] = $OLDCSVFILEPATH . $folder . '/New_Linnworks_Products-' . $time . '.zip';
}

echo "<div id=archive class=form_section>";
echo "<ul>";
foreach ($archive as $year => $m) {
    echo "<li class=year>{$year}<ul>";
    foreach ($m as $month => $d) {
        echo "<li class='month'>" . monthNumberToName($month) . ' ' . $year . "<ul>";
        foreach ($d as $day => $t) {
            echo "<li class='day'>". addOrdinalSuffix($day) .  ' ' . monthNumberToName($month) . ' ' . $year . "<ul>";
            foreach ($t as $time => $file) {
                $arguments = '"' . $year . '", "' . $month . '", "' . $day . '", "' . $time . '"';
                echo "<li class=button ><input type='button' value='{$time}' onclick='getZip($arguments)' /></li>";
            }
            echo "</ul>";
        }
        echo "</li></ul>";
    }
    echo "</li></ul>";
}
echo "</ul>";
echo "</div>";

?>
<script>
    function getZip(year, month, day, time) {
        targetUrl = "get_archived_zip.php";
        
        window.location.replace(targetUrl + '?year=' + year + '&month=' + month + '&day=' + day + '&time=' + time);
    }
    
    $(function() {
    // Find list items representing folders and
    // style them accordingly.  Also, turn them
    // into links that can expand/collapse the
    // tree leaf.
    $('li > ul').each(function(i) {
        // Find this list's parent list item.
        var parent_li = $(this).parent('li');

        // Style the list item as folder.
        parent_li.addClass('folder');

        // Temporarily remove the list from the
        // parent list item, wrap the remaining
        // text in an anchor, then reattach it.
        var sub_ul = $(this).remove();
        parent_li.wrapInner('<a/>').find('a').click(function() {
            // Make the anchor toggle the leaf display.
            sub_ul.toggle();
        });
        parent_li.append(sub_ul);
    });

    // Hide all lists except the outermost.
    $('ul ul').hide();
});
    
    //$('#archive ul').find('ul').hide();
    //
    //$('.year').click(function() {
    //    $(this).children().toggle();
    //    return false;
    //});
    //
    //$('.month').click(function() {
    //    $(this).children().toggle();
    //    return false;
    //});
    
</script>

<?php
require_once($CONFIG['footer']);
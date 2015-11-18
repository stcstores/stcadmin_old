<?php
namespace LSPHP;

class HTMLTools {
    public function arrayToRadioInputs($name, $valueArray, $prefix = '', $suffix = '')
    {
        $i = 1;
        foreach ($valueArray as $value) {
            echo $prefix;
            echo "<input id='{$name}{$i}' name='{$name}' value='{$value}' type='radio'";
            if ($i == 1) {
                echo " checked ";
            }
            echo ">{$value}";
            $i++;
            echo $suffix;
        }
    }

    public function arrayToSelectInputs($name, $valueArray, $default = null)
    {
        echo "<select id={$name} name={$name}>";
        foreach ($valueArray as $value) {
            echo "<option value='{$value}' ";
            if ($value == $default) {
                echo "selected ";
            }
            echo ">{$value}</option>";
            echo "{$value}";
        }
        echo "</select>";
    }
}

<?php
class Brooklyn extends Filter
{
    function __construct()
    {
        $this->curves = new stdClass();
        $this->curves->r = "0,0 24,39 89,117 192,231 222,247 255,255 255,0";
        $this->curves->g = "0,0 29,16 58,58 81,105 132,201 160,221 255,255 255,0";
        $this->curves->b = "0,0 33,34 70,106 95,138 124,156 173,184 204,205 228,213 255,215 255,0";
    }
}
?>

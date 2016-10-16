<?php
class Nineteen77 extends Filter
{
    function __construct()
    {
        $this->curves = new stdClass();
        $this->curves->r = "0,0 0,50 123,168 255,255 255,0";
        $this->curves->g = "0,0 0,27 115,115 255,255 255,0";
        $this->curves->b = "0,0 0,19 104,114 182,161 255,193 255,0";
    }
}
 ?>

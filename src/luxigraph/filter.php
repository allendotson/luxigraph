<?php
class Filter
{
    protected $curves;
    public function GetChannels()
    {
        return $this->curves;
    }

    public function Run()
    {
        return "ProcessCurves";
    }
}
?>

<?php
require_once "filter.php";
require_once "./filters/nashville.php";
require_once "./filters/nineteen77.php";
require_once "./filters/maven.php";
require_once "./filters/brooklyn.php";

class InstagraphFilters
{
    public function Initialize($_name)
    {
        switch(strtolower($_name))
        {
            case "nashville":
                $filter = new Nashville;
            break;
            case "nineteen77":
                $filter = new Nineteen77;
            break;
            case "maven":
                $filter = new Maven;
            break;
            case "brooklyn":
                $filter = new Brooklyn;
            break;
        }
        return $filter;
    }
}
?>

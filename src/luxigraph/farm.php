<?php namespace Luxigraph;

foreach (glob("filters/*.php") as $filename)
{
    require_once $filename;
}

class Farm
{
    public function Process($image, $name)
    {
        switch(strtolower($name))
        {
            case "nashville":
                $filter = new Nashville;
				$image = $filter->Process($image);
            break;
            case "1977":
                $filter = new Nineteen77;
				$image = $filter->Process($image);
            break;
            case "maven":
                $filter = new Maven;
				$image = $filter->Process($image);
            break;
            case "brooklyn":
                $filter = new Brooklyn;
				$image = $filter->Process($image);
            break;
        }
        return $image;
    }
}
?>

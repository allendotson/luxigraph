<?php
require_once "luxigraph_filters.php";

class Luxigraph
{
    protected $image, $temporary;
    public $_prefix = 'IMG';

    private function SetImage($image)
    {
        $this->image = $image;
        $this->temporary = $image;
    }

    public function process($image, $name)
    {
        $this->SetImage($image);

        $filters = new LuxigraphFilters;
        $filter = $filters->Initialize($name);
        $this->{$filter->Run()}($this->temporary, $this->temporary, $filter);
        return;
    }

    public function ProcessCurves($input_image, $output_image, $filter)
    {
        $points = $filter->GetChannels();

        $command = "convert $input_image ";
        if ($points->r !== "")
        {
            $command .= " ( -size 256x256 xc:black -fill white -draw 'polygon 0,0 $points->r 255,0' -crop 256x255+0+1 +repage -flip -scale 256x1! ) -channel R -clut";
        }

        if ($points->g !== "")
        {
            $command .= " ( -size 256x256 xc:black -fill white -draw 'polygon 0,0 $points->g 255,0' -crop 256x255+0+1 +repage -flip -scale 256x1! ) -channel G -clut";
        }

        if ($points->b !== "")
        {
            $command .= " ( -size 256x256 xc:black -fill white -draw 'polygon 0,0 $points->b 255,0' -crop 256x255+0+1 +repage -flip -scale 256x1! ) -channel B -clut";
        }
        $command .= " $output_image";

        $this->execute($command);
        return;
    }

    private function execute($_command)
    {
        # remove newlines and convert single quotes to double to prevent errors
        $_command = str_replace(array("\n", "'"), array('', '"'), $_command);
        # replace multiple spaces with one
        $_command = preg_replace('#(\s){2,}#is', ' ', $_command);
        # escape shell metacharacters
        $_command = escapeshellcmd($_command);
        # execute convert program
        return shell_exec($_command);
    }
}
?>

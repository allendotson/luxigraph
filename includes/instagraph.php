<?php
// requires "instagraphfilters.php";

class Instagraph
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

        $filters = new InstagraphFilters;
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

class InstagraphFilters
{
    public function Initialize($_name)
    {
        switch(strtolower($_name))
        {
            case "normal":
                $filter = new Normal;
            break;
            case "nashville":
                $filter = new Nashville;
            break;
            case "1977":
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

class Nashville extends Filter
{
    function __construct()
    {
        $this->curves = new stdClass();
        $this->curves->r = "0,0 37,31 70,68 152,200 180,232 210,248 255,255 255,0";
        $this->curves->g = "0,0 57,104 87,141 120,169 185,207 255,239 255,0";
        $this->curves->b = "0,0 0,35 65,107 103,134 162,156 255,188 255,0";
    }
}

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

class Maven extends Filter
{
    function __construct()
    {
        $this->curves = new stdClass();
        $this->curves->r = "0,0 43,36 73,41 124,109 156,173 178,209 215,229 255,255 255,0";
        $this->curves->g = "0,0 59,61 99,112 154,154 213,219 255,255 255,0";
        $this->curves->b = "0,0 40,39 68,71 108,105 176,111 255,191 255,0";
    }
}

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

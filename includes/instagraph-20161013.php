<?php
class Instagraph
{
  public $_input = null;
  public $_prefix = 'IMG';
  private $_tmp = null;

  public function process($filter)
  {
    $method = 'filter' . $filter;
    if (method_exists($this, $method))
    {
      $this->_tmp = $this->_input;
      $this->{$method}();
    }
    return false;
  }

  public function execute($command)
  {
    # remove newlines and convert single quotes to double to prevent errors
    $command = str_replace(array("\n", "'"), array('', '"'), $command);
    # replace multiple spaces with one
    $command = preg_replace('#(\s){2,}#is', ' ', $command);
    # escape shell metacharacters
    $command = escapeshellcmd($command);
    # execute convert program
    return shell_exec($command);
  }

  /** ACTIONS */

  public function colortone($color, $level, $type = 0)
  {
    $args[0] = $level;
    $args[1] = 100 - $level;
    $negate = $type == 0 ? '-negate' : '';
    $this->execute("convert
        {$this->_tmp} -set colorspace RGB
        ( -clone 0 -fill $color -colorize 100% )
        ( -clone 0 -colorspace gray $negate )
        -compose blend -define compose:args=$args[0],$args[1] -composite
        {$this->_tmp}");
  }

  public function multiply($color, $opacity = '100%')
  {
    $this->execute("convert
        {$this->_tmp} -set colorspace RGB
        ( -clone 0 -fill $color $opacity )
        -compose multiply -composite
        {$this->_tmp}");
  }

  public function colordodge($color, $opacity = '100%')
  {
    $this->execute("convert
        {$this->_tmp} -set colorspace RGB
        ( -clone 0 -fill $color $opacity )
        -compose blend -composite
        {$this->_tmp}");
  }

  public function border($color = 'black', $width = 20)
  {
    $this->execute("convert $this->_tmp -bordercolor $color -border {$width}x{$width} $this->_tmp");
  }

  public function frame($frame)
  {
    $frame = dirname(realpath(__FILE__)) . '/' . $frame;
    $this->execute("convert $this->_tmp ( $frame -resize {$this->_width}x{$this->_height}! -unsharp 1.5×1.0+1.5+0.02 ) -flatten $this->_tmp");
  }

  public function vignette($color_1 = 'none', $color_2 = 'black', $crop_factor = 1.5)
  {
    $crop_x = floor($this->_width * $crop_factor);
    $crop_y = floor($this->_height * $crop_factor);
    $this->execute("convert
        ( {$this->_tmp} )
        ( -size {$crop_x}x{$crop_y}
        radial-gradient:$color_1-$color_2
        -gravity center -crop {$this->_width}x{$this->_height}+0+0 +repage )
        -compose multiply -flatten
        {$this->_tmp}");
  }

  /** FILTER METHODS */

  public function filterGotham()
  {
    $this->execute("convert $this->_tmp -modulate 120,10,100 -fill #222b6d -colorize 20 -gamma 0.5 -contrast -contrast $this->_tmp");
    $this->border($this->_tmp);
  }

  public function filterToaster()
  {
    $this->colortone('#330000', 100, 0);
    $this->execute("convert $this->_tmp -modulate 150,80,100 -gamma 1.2 -contrast -contrast $this->_tmp");
    $this->vignette('none', 'LavenderBlush3');
    $this->vignette('#ff9966', 'none');
  }

  public function filterNashville()
  {
   	$this->colortone('#151d58', 40, 0);
    $this->colortone('#f1dfc4', 65, 1);
    $this->execute("convert $this->_tmp -modulate 118,200,100 -auto-gamma $this->_tmp");
    //$this->frame('Assets/Frames/Nashville');
  }

  public function filterLomo()
  {
    $command = "convert $this->_tmp -channel R -level 33% -channel G -level 33% $this->_tmp";
    $this->execute($command);
    $this->vignette();
  }

  public function filterKelvin()
  {
    $this->execute("convert
        ( $this->_tmp -auto-gamma -modulate 120,50,100 )
        ( -size {$this->_width}x{$this->_height} -fill rgba(255,153,0,0.5) -draw 'rectangle 0,0 {$this->_width},{$this->_height}' )
        -compose multiply
        $this->_tmp");
    //$this->frame('Assets/Frames/Kelvin');
  }

  public function filterTiltShift()
  {
    $this->execute("convert
        ( $this->_tmp -gamma 0.75 -modulate 100,130 -contrast )
        ( +clone -sparse-color Barycentric '0,0 black 0,%h white' -function polynomial 4,-4,1 -level 0,50% )
        -compose blur -set option:compose:args 5 -composite
        $this->_tmp");
  }

  public function filterDandilyon()
  {
    $this->colortone('#0000ff', 50);
    $this->execute("convert
        {$this->_tmp} -set colorspace RGB
        ( -clone 0 -brightness-contrast 18,-2 )
        -flatten
        {$this->_tmp}");
    $this->multiply('#f4eabd');
    $this->vignette('#ffffff', 'none', 1);
  }

  public function filterCurvy()
  {
      //$points = "0,0 75,46 202,229 255,255";
      //$points = "0,0 255,255";
      //$command = "convert $this->_tmp -channel g -function polynomial '4,-4,1' $this->_tmp";
      $points_red = "70,68 180,232";
      $points_green = "57,104 120,169 255,239";
      $points_blue = "0,35 65,107 162,156 255,188";

      $command = "convert $this->_tmp
        ( -size 256x256 xc:black -fill white -draw 'polygon 0,0 $points_red 255,0' -crop 256x255+0+1 +repage -flip -scale 256x1! ) -channel R -clut
        ( -size 256x256 xc:black -fill white -draw 'polygon 0,0 $points_green 255,0' -crop 256x255+0+1 +repage -flip -scale 256x1! ) -channel G -clut
        ( -size 256x256 xc:black -fill white -draw 'polygon 0,0 $points_blue 255,0' -crop 256x255+0+1 +repage -flip -scale 256x1! ) -channel B -clut
        $this->_tmp";
      $this->execute($command);
  }
}

class Filter
{
    protected $points;
    public function Channel()
    {
        return $this->points;
    }
}

class Nashville extends Filter
{
    function __construct()
    {
        $this->points = new stdClass();
        $this->points->r = "0,0 37,31 70,68 152,200 180,232 210,248 255,255 255,0";
        $this->points->g = "0,0 57,104 87,141 120,169 185,207 255,239 255,0";
        $this->points->b = "0,0 0,35 65,107 103,134 162,156 255,188 255,0";
    }
}

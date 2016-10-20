<?php namespace Luxigraph;
class Brooklyn extends Filter
{
	private $curves;

    public function __construct()
    {
        $this->curves = new \stdClass();
        $this->curves->r = [
			["x" => 0, "y" => 0],
			["x" => 24, "y" => 39],
			["x" => 89, "y" => 117],
			["x" => 192, "y" => 231],
			["x" => 222, "y" => 247],
			["x" => 255, "y" => 255],
			["x" => 255, "y" => 0]
		];
        $this->curves->g = [
			["x" => 0, "y" => 0],
			["x" => 29, "y" => 16],
			["x" => 58, "y" => 58],
			["x" => 81, "y" => 105],
			["x" => 132, "y" => 201],
			["x" => 160, "y" => 221],
			["x" => 255, "y" => 255],
			["x" => 255, "y" => 0]
		];
        $this->curves->b = [
			["x" => 0, "y" => 0],
			["x" => 33, "y" => 34],
			["x" => 70, "y" => 106],
			["x" => 95, "y" => 138],
			["x" => 124, "y" => 156],
			["x" => 173, "y" => 184],
			["x" => 204, "y" => 205],
			["x" => 228, "y" => 213],
			["x" => 255, "y" => 215],
			["x" => 255, "y" => 0]
		];
    }

	public function Process($image = null)
	{
		if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		$image = $this->Curves($image, $this->curves);
		return $image;
	}
}
?>

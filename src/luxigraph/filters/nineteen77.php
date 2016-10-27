<?php namespace Luxigraph;

class Nineteen77 extends Filter
{
	private $curves;

	public function __construct()
	{
		$this->curves = new \stdClass();
		$this->curves->r = [
			["x" => 0, "y" => 0],
			["x" => 0, "y" => 50],
			["x" => 123, "y" => 168],
			["x" => 255, "y" => 255],
			["x" => 255, "y" => 0]
		];
	    $this->curves->g = [
			["x" => 0, "y" => 0],
			["x" => 0, "y" => 27],
			["x" => 115, "y" => 115],
			["x" => 255, "y" => 255],
			["x" => 255, "y" => 0]
		];
		$this->curves->b = [
			["x" => 0, "y" => 0],
			["x" => 0, "y" => 19],
			["x" => 104, "y" => 114],
			["x" => 182, "y" => 161],
			["x" => 255, "y" => 193],
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

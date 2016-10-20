<?php namespace Luxigraph;
require_once "filters/brooklyn.php";
require_once "filters/nineteen77.php";

class Filter
{
    public function Process($image = null)
	{
		return $image;
	}

	public function Curves($image = null, $curves = null)
	{
		if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		if ($curves == "" || $curves == null)
        {
            throw new \Exception("Curves cannot be empty");
        }

		$polyRed = new \ImagickDraw();
		$polyRed->setFillColor(new \ImagickPixel("white"));
		$polyRed->polygon($curves->r);
		$rampRed = new \Imagick();
		$rampRed->newImage(255, 255, new \ImagickPixel("black"));
		$rampRed->drawImage($polyRed);
		$rampRed->scaleImage(255, 1);
		$image->clutImage($rampRed, \Imagick::CHANNEL_RED);

		$polyGreen = new \ImagickDraw();
		$polyGreen->setFillColor(new \ImagickPixel("white"));
		$polyGreen->polygon($curves->g);
		$rampGreen = new \Imagick();
		$rampGreen->newImage(255, 255, new \ImagickPixel("black"));
		$rampGreen->drawImage($polyGreen);
		$rampGreen->scaleImage(255, 1);
		$image->clutImage($rampGreen, \Imagick::CHANNEL_GREEN);

		$polyBlue = new \ImagickDraw();
		$polyBlue->setFillColor(new \ImagickPixel("white"));
		$polyBlue->polygon($curves->b);
		$rampBlue = new \Imagick();
		$rampBlue->newImage(255, 255, new \ImagickPixel("black"));
		$rampBlue->drawImage($polyBlue);
		$rampBlue->scaleImage(255, 1);
		$image->clutImage($rampBlue, \Imagick::CHANNEL_BLUE);

		return $image;
	}

	public function Colorize($image = null, $color = "rgba(255, 255, 255, 1)", $composition = \Imagick::COMPOSITE_MULTIPLY)
	{
		if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		$overlay = new \Imagick();
		$overlay->newPseudoImage($image->getImageWidth(), $image->getImageHeight(), "canvas:" . $color);
		$image->compositeImage($overlay, $composition, 0, 0);

		return $image;
	}

	public function Levels($image = null)
	{
		return $image;
	}

	public function BrightnessContrast($image = null)
	{
		return $image;
	}
}
?>

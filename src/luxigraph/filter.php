<?php namespace Luxigraph;

class Filter
{
    public function Process($image = null)
	{
		return $image;
	}

	public function Curves($image = null, $curves = null)
	{
		/*
		@param array $curves array of curve points
		@return magickimage
		*/
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

	public function Colorize($image = null, $color = "rgba(255, 255, 255, 1)", $blending = \Imagick::COMPOSITE_MULTIPLY)
	{
		/*
		@param string $color overlay color
		@param int $blending blending method
		@return magickimage
		*/
		if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		$overlay = new \Imagick();
		$overlay->newPseudoImage($image->getImageWidth(), $image->getImageHeight(), "canvas:" . $color);
		$image->compositeImage($overlay, $blending, 0, 0);

		return $image;
	}

	public function Levels($image = null, $black = 0.0, $white = 100.0, $gamma = 1.0)
	{
		/*
		@param float $black percentage of black point
		@param float $white percentage of white point
		@param float $gamma gamma value
		@return magickimage
		*/
		if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		$quantum = $image->getQuantum();
		$image->levelImage($black, $gamma, $quantum * $white);

		return $image;
	}

	public function BrightnessContrast($image = null, $brightness = 0.0, $contrast = 0.0)
	{
		/*
		@param float $brightness percentage of change
		@param float $contrast percentage of change
		@return magickimage
		*/
		if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		$image->brightnessContrastImage($brightness, $contrast);

		return $image;
	}

	public function Vignette($image = null, $texture = "vignettes/black-200.png", $opacity = 100.0, $scale = 0.0)
	{
		/*
		@param string $texture relative path to texture
		@param float $opacity opacity of vignette
		@param float $scale amount of pixels to scale vignette
		@return magickimage
		*/
		if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		$layer = new \Imagick();
		$layer->readImage($texture);
		$layer->scaleImage($image->getImageWidth() + $scale, $image->getImageHeight() + $scale);
		$layer->evaluateImage(\Imagick::EVALUATE_MULTIPLY, $opacity / 100, \Imagick::CHANNEL_ALPHA);
		$image->compositeImage($layer, \Imagick::COMPOSITE_ATOP, 0, 0);

		return $image;
	}

	public function LightLeak($image = null)
	{
		if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		return $image;
	}

	public function Texture($image = null, $texture = "textures/scratches-01.jpg", $opacity = 50.0, $blending = \Imagick::COMPOSITE_SOFTLIGHT)
	{
		/*
		@param string $texture relative path to texture
		@param float $opacity opacity of vignette
		@param int $blending blending method
		@return magickimage
		*/
		if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		$layer = new \Imagick();
		$layer->readImage($texture);
		$layer->scaleImage($image->getImageWidth() + $scale, $image->getImageHeight() + $scale);
		$layer->setImageAlphaChannel(\Imagick::ALPHACHANNEL_SET);
		$layer->evaluateImage(\Imagick::EVALUATE_MULTIPLY, $opacity / 100, \Imagick::CHANNEL_ALPHA);
		$image->compositeImage($layer, $blending, 0, 0);

		return $image;
	}

	public function Saturation($image = null, $saturation = 100.0)
	{
		/*
		@param float $saturation level of saturation
		@return magickimage
		*/
		if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		$image->modulateImage(100.0, $saturation, 100.0);

		return $image;
	}

	public function GetPercentage($portion = 0, $total = 100)
	{
		$ret = 0;
		if (is_numeric($portion) && is_numeric($total))
		{
			$ret = $portion / $total;
		}
		return $ret;
	}
}
?>

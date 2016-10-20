<?php namespace Luxigraph;
require_once "luxigraph_filters.php";

class Image
{
    public $temporary = null;
    private $configuration = array();

    private function __construct(array $configuration = array())
    {
        $this->configuration = array_merge(array(
            "prefix" => "lux",
            "quality" => 100
        ), $configuration);

        return $this;
    }

    public function GetRemote($url = null)
    {
		/*
		@param string $url a url of remote image
		@return binary image blob
		*/
        if ($url == "" || $url == null)
        {
            throw new Exception("URL cannot be empty");
        }
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		$results = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$results = explode("\n", trim($results));
		foreach($results as $line)
        {
			if (strtok($line, ":") == "Content-Type")
            {
				$parts = explode(":", $line);
				$mime = trim($parts[1]);
			}
		}

		if ($status == "0")
        {
			throw new Exception("Unknown error");
		}
        elseif ($status != "200")
        {
    		throw new Exception($status);
		}

		if ($mime != "image/jpeg")
        {
			throw new Exception("Provided image was not a image/jpeg");
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		$bin = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return $bin;
	}

	public function ConvertToMagick($magick = new ImageMagick(), $bin = null)
	{
		/*
		@param ImageMagick $magick instance of ImageMagick
		@param string $bin a binary image
		@return ImageMagick image
		*/
        if ($bin == "" || $bin == null)
        {
            throw new Exception("Binary image cannot be empty");
        }

		$image = $magick->readImageBlob($bin);
		return $image;
	}

	public function ConvertToBinary($magick = new ImageMagick(), $image = null)
	{
		/*
		@param ImageMagick $magick instance of ImageMagick
		@param ImageMagick $image an image
		@return binary image blob
		*/
        if ($image == "" || $image == null)
        {
            throw new Exception("Image cannot be empty");
        }

		$bin = $magick->getImageBlob($image);
		return $bin;
	}

    public function Encode($bin = null)
    {
		/*
		@param string $bin binary image
		@return base64 encoded image
		*/
        if ($bin == "" || $bin == null)
        {
            throw new Exception("Could not encode empty binary");
        }
		return base64_encode($bin);
	}

	public function Decode($enc = null)
    {
		/*
		@param string $enc base64 encoded image
		@return binary image
		*/
        if ($enc == "" || $enc == null)
        {
            throw new Exception("Could not decode empty encoded");
        }
		return base64_decode($enc);
	}

	/*
    public function Display($image = null)
    {
        if ($image == "" || $image == null)
        {
            throw new Exception("Cannot display empty image");
        }
		header("Content-Type: image/jpg");
		$disp = imagecreatefrompng($image);
		imagejpeg($disp, NULL, 100);
		exit();
	}

    public function Process($image, $name)
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
	*/
}
?>

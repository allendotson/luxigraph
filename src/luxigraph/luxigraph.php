<?php namespace Luxigraph;

class Image
{
    public $temporary = null;
    private $configuration = array();

    public function __construct(array $configuration = array())
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
            throw new \Exception("URL cannot be empty");
        }
		$ch = curl_init();
		curl_setopt($ch, \CURLOPT_URL, $url);
		curl_setopt($ch, \CURLOPT_HEADER, 1);
		curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, \CURLOPT_NOBODY, 1);
		$results = curl_exec($ch);
		$status = curl_getinfo($ch, \CURLINFO_HTTP_CODE);
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
			throw new \Exception("Unknown error");
		}
        elseif ($status != "200")
        {
    		throw new \Exception($status);
		}

		if ($mime != "image/jpeg")
        {
			throw new \Exception("Provided image was not a image/jpeg");
		}

		$ch = curl_init();
		curl_setopt($ch, \CURLOPT_URL, $url);
		curl_setopt($ch, \CURLOPT_HEADER, 0);
		curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		$bin = curl_exec($ch);
		$status = curl_getinfo($ch, \CURLINFO_HTTP_CODE);
		curl_close($ch);

		return $bin;
	}

	public function BinaryToMagick($bin = null)
	{
		/*
		@param string $bin a binary image
		@return ImageMagick image
		*/
        if ($bin == "" || $bin == null)
        {
            throw new \Exception("Binary image cannot be empty");
        }

		$image = new \Imagick();
		$image->readImageBlob($bin);
		return $image;
	}

	public function MagickToBinary($image = null)
	{
		/*
		@param ImageMagick $image an image
		@return binary image blob
		*/
        if ($image == "" || $image == null)
        {
            throw new \Exception("Image cannot be empty");
        }

		$bin = $image->getImageBlob();
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
            throw new \Exception("Could not encode empty binary");
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
            throw new \Exception("Could not decode empty encoded");
        }
		return base64_decode($enc);
	}
}
?>

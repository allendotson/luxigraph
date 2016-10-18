<?php
require_once "luxigraph_filters.php";

class Luxigraph
{
    protected $image, $temporary;
    public $_prefix = 'IMG';

    public function GetRemoteImage($_url)
    {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $_url);
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
		curl_setopt($ch, CURLOPT_URL, $_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		$results = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$this->image = imagecreatefromstring($results);
	}

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

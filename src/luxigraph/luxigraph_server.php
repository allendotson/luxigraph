<?php
class LuxigraphServer {
    protected $image, $tmp;

	public function GetImage($_url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $_url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		$results = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$results = explode("\n", trim($results));
		foreach($results as $line) {
			if (strtok($line, ":") == "Content-Type") {
				$parts = explode(":", $line);
				$mime = trim($parts[1]);
			}
		}

		if ($status == "0") {
			$message->status = "failure";
			$message->error = "Unknown error";
			$message = json_encode($message);
			header("Content-Type: application/json");
			exit($message);
		} elseif ($status != "200") {
			$message->status = "failure";
			$message->error = $status;
			$message = json_encode($message);
			header("Content-Type: application/json");
			exit($message);
		}

		if ($mime != "image/jpeg") {
			$message->status = "failure";
			$message->error = "Provided image was not a image/jpeg";
			$message = json_encode($message);
			header("Content-Type: application/json");
			exit($message);
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

	public function SaveTemporary() {
		$this->tmp = tempnam('/tmp', 'INST');
		imagepng($this->image, $this->tmp, 0);
	}

	public function GetTemporary()
	{
		return $this->tmp;
	}

	public function SaveImage() {
		$this->image = $this->tmp;
	}

	public function ShowImage() {
		header("Content-Type: image/jpg");
		$image = imagecreatefrompng($this->image);
		imagejpeg($image, NULL, 100);
		exit();
	}

	public function CaptureImage() {
		ob_start();
		$image = imagecreatefrompng($this->image);
		imagejpeg($image, NULL, 100);
		$this->image = ob_get_contents();
		ob_end_clean();
	}

	public function EncodeImage($_image) {
		return base64_encode($_image);
	}

	public function DecodeImage($_image) {
		return base64_decode($_image);
	}
}
?>

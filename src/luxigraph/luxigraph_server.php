<?php
class LuxigraphServer
{
    protected $image, $tmp;

	public function SaveTemporary()
    {
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

<?php
class Luxigraph {
	private $apiurl = "http://luxigraph.sites918.com/process.php";
	private $apiuser = "mnjpgqr";
	private $apikey = "nplB86B7RK0uAAjq4f8oBxxOY62KuMqb";
	private $image = NULL;

	private function ExecuteCommand($_json) {
		$ch = curl_init($this->apiurl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $_json);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 0);
		$results = curl_exec($ch);
		curl_close($ch);
		return json_decode($results);
	}

	public function EncodeImage($_image) {
		return base64_encode($_image);
	}

	public function DecodeImage($_image) {
		return base64_decode($_image);
	}

	public function ProcessImage($_url, $_filter, $_size, $_strength) {
		$command = new stdClass();
		$command->filter = new stdClass();
		$command->service = "process";
		$command->action = "filter";
		$command->url = $_url;
		$command->filter->size = $_size;
		$command->filter->name = $_filter;
		$command->filter->strength = $_strength;
		$command->id = $this->apiuser;
		$command->key = $this->apikey;
		$data = json_encode($command);

		$response = $this->ExecuteCommand($data);
		$this->image = imagecreatefromstring($this->DecodeImage($response->{"image"}));
	}

	public function DisplayImage() {
		header("Content-Type: image/jpg");
		imagejpeg($this->image, NULL, 100);
		exit();
	}
}

$luxigraph = new Luxigraph;
$luxigraph->ProcessImage("http://cms.sites918.com/common/uploads/test_sites918_com/media/52-water-ocean-girl-forest.jpg", "1977", "full", 100);
$luxigraph->DisplayImage();
?>

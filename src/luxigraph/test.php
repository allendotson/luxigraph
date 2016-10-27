<?php
	require_once "luxigraph.php";
	require_once "filter.php";
	require_once "farm.php";

	$luximage = new \Luxigraph\Image();
	$image = $luximage->GetRemote("http://cms.sites918.com/common/uploads/test_sites918_com/media/52-water-ocean-girl-forest.jpg");
	$image = $luximage->BinaryToMagick($image);

	$farm = new \Luxigraph\Farm();
	$image = $farm->Process($image, "1977");

	header("Content-Type: image/jpeg");
	$image = $luximage->MagickToBinary($image);
	echo $image;
?>

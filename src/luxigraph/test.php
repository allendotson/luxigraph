<?php
    require_once "luxigraph.php";
	require_once "filter.php";

	$luximage = new \Luxigraph\Image();
	$image = $luximage->GetRemote("http://cms.sites918.com/common/uploads/test_sites918_com/media/52-water-ocean-girl-forest.jpg");
	$image = $luximage->BinaryToMagick($image);

	$filter = new \Luxigraph\Brooklyn();
	$image = $filter->Process($image);

	header("Content-Type: image/jpeg");
	$image = $luximage->MagickToBinary($image);
	echo $image;
?>

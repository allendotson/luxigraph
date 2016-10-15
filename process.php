<?php
set_time_limit(0);
require "includes/luxigraphserver.php";
require "includes/instagraph.php";

# Capture JSON
$data = file_get_contents("php://input");
$data = json_decode($data);

# toaster, nashville, lomo, kelvin, tiltshift, dandilyon
$_USERID = $data->{"id"};
$_USERKEY = $data->{"key"};
$_SERVICE = $data->{"service"};
$_ACTION = $data->{"action"};
$_URL = $data->{"url"};
$_FILTERNAME = $data->{"filter"}->{"name"};
$_FILTERSIZE = $data->{"filter"}->{"size"};
$_FILTERSTRENGTH = $data->{"filter"}->{"strength"};

// $_USERID = "mnjpgqr";
// $_URL = "http://cms.sites918.com/common/uploads/www_woodlandchristianchurch_com/media/38-pexels-photo-110095.jpeg";
// $_FILTERNAME = "1977";
// $_FILTERSIZE = "full";
// $_FILTERSTRENGTH = 100;

$luxigraph = new LuxigraphServer;
$instagraph = new Instagraph;

# Get the photo
$luxigraph->GetImage($_URL);

# Save as a temporary png
$luxigraph->SaveTemporary();

# Process temporary png
$instagraph->process($luxigraph->GetTemporary(), $_FILTERNAME);

# Save as image file
$luxigraph->SaveImage();

# Show png as an jpeg
//$luxigraph->ShowImage();
$luxigraph->CaptureImage();

# Output image
$command = new stdClass();
$command->status = "success";
$command->image = $luxigraph->EncodeImage($luxigraph->image);
$response = json_encode($command);
header("Content-Type: application/json");
echo $response;
?>

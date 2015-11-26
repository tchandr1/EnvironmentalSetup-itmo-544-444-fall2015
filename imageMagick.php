<?php

header('Content-type: image/jpeg');

$images = new Imagick(glob('images/*.jpg'));

foreach($images as $image){
// If 0 is provided as a width or height parameter,
// aspect ratio is maintained

$image->thumbnailImage(1024, 0);

}
$images->writeImages('images/out.jpg',false);
//echo $image;

?>


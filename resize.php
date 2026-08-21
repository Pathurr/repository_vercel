<?php
$src = 'public/logo.png';
$dest = 'public/favicon.png';

if (!file_exists($src)) {
    die("Source file not found.");
}

$info = getimagesize($src);
$mime = $info['mime'];

if ($mime == 'image/png') {
    $image = imagecreatefrompng($src);
} elseif ($mime == 'image/jpeg') {
    $image = imagecreatefromjpeg($src);
} else {
    die("Unsupported image type");
}

$width = imagesx($image);
$height = imagesy($image);

$new_width = 32;
$new_height = 32;

$new_image = imagecreatetruecolor($new_width, $new_height);

// Preserve transparency for PNG
imagealphablending($new_image, false);
imagesavealpha($new_image, true);
$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);

imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

imagepng($new_image, $dest);
imagedestroy($image);
imagedestroy($new_image);
echo "Favicon generated successfully.";

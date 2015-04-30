<?php

function image_create($path) {
    $fp = fopen($path, 'rb');
    $data = fread($fp, filesize($path));
    $img = imagecreatefromstring($data);
    fclose($fp);
    return $img;
}

function image_destroy($img) {
    imagedestroy($img);
}

function image_resize($img, $maxWidth, $maxHeight) {

    $width = imagesx($img);
    $height = imagesy($img);

    if ($width / $height < $maxWidth / $maxHeight) {
        $newHeight = $maxHeight;
        $newWidth = $width * $maxHeight / $height;
    } else {
        $newWidth = $maxWidth;
        $newHeight = $height * $maxWidth / $width;
    }

    // Create new image container
    $resized = imagecreatetruecolor($newWidth, $newHeight);

    // Copy resampled for higher quality result
    imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    return $resized;
}

function image_resize_crop($img, $width, $height) {

    $currentWidth = imagesx($img);
    $currentHeight = imagesy($img);

    if ($currentWidth / $currentHeight > $width / $height) {
        $newHeight = $height;
        $newWidth = $currentWidth * $height / $currentHeight;
    } else {
        $newWidth = $width;
        $newHeight = $currentHeight * $width / $currentWidth;
    }

    $resized = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $currentWidth, $currentHeight);

    $cropped = imagecreatetruecolor($width, $height);
    imagecopy($cropped, $resized, 0, 0, $newWidth / 2 - $width / 2, $newHeight / 2 - $height / 2, $width, $height);

    return $cropped;
}

function image_write($data, $path) {
    imagejpeg($data, $path, 85);
}

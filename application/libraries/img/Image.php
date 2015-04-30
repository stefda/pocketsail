<?php

class Image {

    private $img;

    private function __construct($img) {
        $this->img = $img;
    }

    public static function create_from_file($path) {
        $fp = fopen($path, 'rb');
        $data = fread($fp, filesize($path));
        $img = imagecreatefromstring($data);
        return new Image($img);
    }

    public function resize($width, $height) {

        $currentWidth = imagesx($this->img);
        $currentHeight = imagesy($this->img);
        $resized = NULL;

        if ($currentWidth / $currentHeight < $width / $height) {
            $newHeight = $height;
            $newWidth = $currentWidth * $height / $currentHeight;
        } else {
            $newWidth = $width;
            $newHeight = $currentHeight * $width / $currentWidth;
        }

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $this->img, 0, 0, 0, 0, $newWidth, $newHeight, $currentWidth, $currentHeight);
        return $resized;
    }

    public function resize_fit($width, $height) {

        $currentWidth = imagesx($this->img);
        $currentHeight = imagesy($this->img);
        $resized = NULL;

        if ($currentWidth / $currentHeight > $width / $height) {
            $newHeight = $height;
            $newWidth = $currentWidth * $height / $currentHeight;
        } else {
            $newWidth = $width;
            $newHeight = $currentHeight * $width / $currentWidth;
        }
        
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $this->img, 0, 0, 0, 0, $newWidth, $newHeight, $currentWidth, $currentHeight);

        $fitted = imagecreatetruecolor($width, $height);
        imagecopy($fitted, $resized, 0, 0, $newWidth / 2 - $width / 2, $newHeight / 2 - $height / 2, $width, $height);
        
        return $fitted;
    }
    
    public static function save($data, $path) {
        imagejpeg($data, $path, 85);
    }

}

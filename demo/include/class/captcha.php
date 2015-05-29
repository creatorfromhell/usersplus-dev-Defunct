<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/7/14
 * Time: 12:22 PM
 * Version: Beta 1
 * Last Modified: 8/7/14 at 1:10 PM
 * Last Modified by Daniel Vidmar.
 */

class Captcha {
    public $code = null;
    public $image = null;

    function __construct($code = null) {
        $this->code = ($code !== null) ? $code : $this->generateCode();
        $this->generateImage();
    }

    public function generateCode() {
        $validchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+123456789";
        $c = "";
        for($i = 0; $i < 6; $i++) {
            $c .= $validchars[rand(0, strlen($validchars) - 1)];
        }
        return $c;
    }

    public function generateImage() {
        if($this->code === null) {
            $this->code = $this->generateCode();
        }
        $this->image = imagecreate(95, 35);
        $colorText = imagecolorallocate($this->image, 255, 255, 255);
        $colorBG = imagecolorallocate($this->image, 82, 139, 185);
        $colorRect = imagecolorallocate($this->image, 163, 163, 163);
        imagefill($this->image, 0, 0, $colorBG);
        imagestring($this->image, 10, 20, 10, $this->code, $colorText);
        imageline($this->image, 20, 12, 38, 20, $colorRect);
        imageline($this->image, 50, 15, 80, 15, $colorRect);
        imageline($this->image, 70, 0, 30, 35, $colorRect);
    }

    public function printImage() {
        ob_start();
        imagejpeg($this->image, NULL, 100);
        $bytes = ob_get_clean();
        echo "<img id='captcha_image' src='data:image/jpeg;base64," . base64_encode($bytes) . "' />";
    }
}
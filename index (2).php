<?php

class CustomColor {
    private $red;
    private $green;
    private $blue;

    public function __construct($red, $green, $blue) {
        $this->setRed($red);
        $this->setGreen($green);
        $this->setBlue($blue);
    }

    public function getRed() {
        return $this->red;
    }

    public function setRed($red) {
        $this->validateColor($red);
        $this->red = $red;
    }

    public function getGreen() {
        return $this->green;
    }

    public function setGreen($green) {
        $this->validateColor($green);
        $this->green = $green;
    }

    public function getBlue() {
        return $this->blue;
    }

    public function setBlue($blue) {
        $this->validateColor($blue);
        $this->blue = $blue;
    }

    private function validateColor($color) {
        if ($color < 0 || $color > 255) {
            throw new InvalidArgumentException('Невірний колір. Значення має бути в діапазоні від 0 до 255.');
        }
    }
    
    public function areColorsEqual(CustomColor $otherColor) {
        return $this->red === $otherColor->getRed() &&
            $this->green === $otherColor->getGreen() &&
            $this->blue === $otherColor->getBlue();
    }

    public static function generateRandomColor() {
        $red = rand(0, 255);
        $green = rand(0, 255);
        $blue = rand(0, 255);
        return new self($red, $green, $blue);
    }

    public function blendColors(CustomColor $otherColor) {
        $mixedRed = ($this->red + $otherColor->getRed()) / 2;
        $mixedGreen = ($this->green + $otherColor->getGreen()) / 2;
        $mixedBlue = ($this->blue + $otherColor->getBlue()) / 2;

        return new self($mixedRed, $mixedGreen, $mixedBlue);
    }
}

$color = new CustomColor(250, 250, 250);
$randomColor = CustomColor::generateRandomColor();
$mixedColor = $color->blendColors($randomColor);

echo $mixedColor->getRed() . PHP_EOL;
echo $mixedColor->getGreen() . PHP_EOL;
echo $mixedColor->getBlue() . PHP_EOL;


<?php
/**
 * Image.php - Generate captcha images
 *
 * Copyright (C) 2015 Ajay Sreedhar <ajaysreedhar468@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

/**
 * Class Steelcode_Captcha_Image
 *
 * Provides necessary functions to generate captcha images
 *
 * @category Steelcode
 * @package Steelcode_Captcha
 */

class Steelcode_Captcha_Image {

    /**
     * Image blur flag
     *
     * @var bool
     */
    private $_blur = true;

    /**
     * Width of captcha image
     *
     * @var int
     */
    private $_width = 200;

    /**
     * Height of captcha image
     *
     * @var int
     */
    private $_height = 80;

    /**
     * Word length of captcha text
     *
     * @var int
     */
    private $_wordLength  = 6;

    /**
     * Image scaling factor
     *
     * @var int
     */
    private $_scale = 3;

	/**
	 * Font size in pixels or points,
	 * depending on GD version
	 *
	 * @var int
	 */
	private $_fontSize = 70;

    /**
     * Chosen foreground color
     *
     * @var
     */
    private $_fgColor;

    /**
     * Used by _drawLine function
     *
     * @var int
     */
    private $_finalTextX;

    /**
     * Text to be written on image
     *
     * @var string
     */
    private $_captchaText = null;

    /**
     * Font file to be used
     *
     * This variable should be set before drawing an image.
     * Else an error message will be displayed.
     *
     * @var string
     */
    private $_fontFile = null;

    /**
     * Error message associated with last
     * failed operation
     *
     * @var string
     */
    private $_errorText = '';

    /**
     * Hexadecimal RGB background color
     *
     * @var array
     */
    private $_backgroundColor = array ( 0xFF, 0xFF, 0xFF );

    /**
     * List of foreground colors to be picked random
     *
     * @var array
     */
    private $_colors = array(
        array( 0x49, 0xBA, 0xD ),
        array( 0xD4, 0xB, 0xB ),
        array( 0x5B, 0x1E, 0xD6 ),
        array( 0xB5, 0x6A, 0x0 ),
        array( 0xF, 0xB8, 0xAC ),
        );

    /**
     * Created captcha image
     *
     * @var resource
     */
    private $_image;

    /**
     * Returns a random text of length _wordLength
     *
     * @return string
     */
    private function _getRandomText() {
        $alphaNumeric   = 'AzBy1CxDw2EvFu3GtHs4IrJq5KpL6MnNm7lPk8QjRi9ShTgUfVeWdXcYbZa';
        $sSize = strlen( $alphaNumeric ) - $this->_wordLength - 1;

        $randomText = substr( $alphaNumeric, rand( 1, $sSize ), $this->_wordLength );

        $shuffledString = str_shuffle( $randomText );

        return $shuffledString;
    }

    /**
     * Write the given text to the image
     *
     * @param $text
     */
    private function _writeCaptchaText( $text ) {
        $colorIndex = rand( 0, count( $this->_colors ) - 1 );
        $color      = $this->_colors[$colorIndex];

        $this->_fgColor = imagecolorallocate( $this->_image, $color[0], $color[1], $color[2] );

        $xCoordinate = round ( ($this->_width * $this->_scale)/5 );
        $yCoordinate = round ( ( $this->_height * 27/40 ) * $this->_scale );

        imagettftext($this->_image, $this->_fontSize, 0, $xCoordinate, $yCoordinate, $this->_fgColor, $this->_fontFile, $text );

        $this->_finalTextX = $xCoordinate;
    }

    /**
     * Make the captcha text irregular
     */
    private function _zigZagCaptchaText() {
        $xp   = $this->_scale * 10 * rand( 1, 3 );
        $rand = rand( 0, 100 );

        $scaledWidth = $this->_width * $this->_scale;

        for ( $i = 0; $i < $scaledWidth; $i++ ) {
            imagecopy( $this->_image, $this->_image,
                $i-1, sin( $rand + $i/$xp ) * ( $this->_scale * 5 ),
                $i, 0, 1, $this->_height * $this->_scale );
        }

        $rand = rand( 0, 100 );

        $yp = $this->_scale * 12 * rand( 1, 3 );

        for ( $i = 0; $i < ( $this->_height * $this->_scale ); $i++ ) {
            imagecopy( $this->_image, $this->_image,
                sin( $rand + $i/$yp ) * ( $this->_scale * 14 ), $i-1,
                0, $i, $this->_width * $this->_scale, 1 );
        }
    }

    /**
     * Scale the image back to original size with respect to scale factor
     */
    private function _scaleBackImage() {
        $reSampled = imagecreatetruecolor( $this->_width, $this->_height );

        imagecopyresampled( $reSampled, $this->_image,
            0, 0, 0, 0, $this->_width, $this->_height,
            ( $this->_width * $this->_scale ), ( $this->_height * $this->_scale ) );

        imagedestroy( $this->_image );
        $this->_image = $reSampled;
    }

    /**
     * Initialize
     */
    private function _initialize() {
        if ( !empty( $this->_image ) ) {
            imagedestroy( $this->_image );
        }

        if ( $this->_fontFile === null ) {
            $this->_errorText = 'No font files are specified!';
            return false;
        }

        $this->_image = imagecreatetruecolor ( $this->_width * $this->_scale,  $this->_height * $this->_scale );

        $bgColor = imagecolorallocate($this->_image,
            $this->_backgroundColor[0],
            $this->_backgroundColor[1],
            $this->_backgroundColor[2] );

        imagefilledrectangle( $this->_image, 0, 0,
            $this->_width * $this->_scale, $this->_height * $this->_scale, $bgColor );

        return true;
    }

    /**
     * Class constructor
	 *
     * @param int $width
     * @param int $height
     * @param string $font
     */
    public function __construct( $width = 200, $height = 70, $font = null ) {
        $this->_width  = $width;
        $this->_height = $height;

        $this->_fontFile = $font;
    }

	/**
	 * Creates a new image
	 * 
	 * @param string $text
	 * @throws Steelcode_Captcha_Exception
	 */
	public function createImage( $text=null ) {
        if ( !$this->_initialize() ) {
			throw new Steelcode_Captcha_Exception( $this->_errorText );
		}

		$this->_captchaText = ( $text === null ) ? $this->_getRandomText() : $text;

        $this->_writeCaptchaText( $this->_captchaText );

        $this->_zigZagCaptchaText();

        if ( $this->_blur && function_exists( 'imagefilter' ) ) {
            imagefilter( $this->_image, IMG_FILTER_GAUSSIAN_BLUR );
        }

        $this->_scaleBackImage();
    }

	/**
	 * Output the created image
	 *
	 * @throws Steelcode_Captcha_Exception
	 */
	public function outputImage() {
        header( "Content-type: image/png" );
        imagepng( $this->_image );

        imagedestroy( $this->_image );
    }

    /**
     * Set the blur flag
     *
     * @param boolean $blur
     */
    public function setBlur( $blur ) {
        $this->_blur = $blur;
    }

    /**
     * Set the captcha image width
     *
     * @param int $width
     */
    public function setWidth( $width ) {
        $this->_width = $width;
    }

    /**
     * Set the captcha image height
     *
     * @param int $height
     */
    public function setHeight( $height ) {
        $this->_height = $height;
    }

    /**
     * Set the captcha text word length
     *
     * @param int $wordLength
     */
    public function setWordLength( $wordLength ) {
        $this->_wordLength = $wordLength;
    }

    /**
     * Set image scaling factor
     *
     * @param int $scale
     */
    public function setScale( $scale ) {
        $this->_scale = $scale;
    }

    /**
     * Manually set captcha text instead of generating
     *
     * @param string $captchaText
     */
    public function setCaptchaText( $captchaText ) {
        $this->_captchaText = $captchaText;
    }

    /**
     * Set the path to the font file to be used
     *
     * @param string $fontFile
     */
    public function setFontFile( $fontFile ) {
        $this->_fontFile = $fontFile;
    }

	/**
	 * Set font size in pixels or points,
	 * depending in GD version
	 *
	 * @param int $fontSize
	 */
	public function setFontSize( $fontSize ) {
		$this->_fontSize = $fontSize;
	}

    /**
     * Set the background color of the captcha image
     *
     * @param array $backgroundColor
     */
    public function setBackgroundColor( $backgroundColor ) {
        $this->_backgroundColor = $backgroundColor;
    }

	/**
	 * Get the generated captcha text
	 *
	 * @return string
	 */
	public function getCaptchaText() {
		return $this->_captchaText;
	}

    /**
     * Get the message associated with  last failed operation
     *
     * @return string
     */
    public function getErrorText() {
        return $this->_errorText;
    }
}


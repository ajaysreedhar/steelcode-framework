<?php
/**
 * Helper.php - Miscellaneous helper functions
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
 * Class Steelcode_Utils_Helper
 *
 * Contains miscellaneous utility helper functions
 *
 * @category Steelcode
 * @package Steelcode_Utils
 */
abstract class Steelcode_Utils_Helper {

	/**
	 * Convert camelCase words to hyphen separated words
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function camelToDashed( $string ) {
		return strtolower( preg_replace( '/([a-zA-Z])(?=[A-Z])/', '$1-', $string ) );
	}

	/**
	 * Convert hyphen separated words to camelCase words
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function dashedToCamel( $string ) {
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	}

	/**
	 * Get a random string of specified length
	 *
	 * @param int $ssize : size of random string
	 * @param string $expression : [optional] expression to specify character combination
	 * 		A - all uppercase letters of English alphabet
	 * 		a - all lowercase letters of English alphabet
	 * 		V - all uppercase vowel letters of English alphabet - AEIOU
	 *		v - all lowercase vowel letters of English alphabet - aeiou
	 * 		C - all uppercase consonant letters of English alphabet
	 *		c - all lowercase consonant letters of English alphabet
	 *		I - integers from 0 to 9
	 *
	 * @return string
	 */
	public static function randomString( $ssize, $expression="AaI" ) {
		$charSets = array (
			'A' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'a' => 'abcdefghijklmnopqrstuvwxyz',
			'V' => 'AEIOU',
			'v' => 'aeiou',
			'C' => 'BCDFGHJKLMNPQRSTVWXYZ',
			'c' => 'bcdfghjklmnpqrstvwxyz',
			'I' => '0123456789'
			);

		$varString = "";

		if ( empty( $expression ) ) {
			$expression = "AaI";
		}

		$expLen   = strlen( $expression );
		$expIndex = 0;

		while ( $expIndex < $expLen ) {
			$expChar   = $expression[$expIndex];
			$varString = str_shuffle( $charSets[$expChar] . $varString );

			$expIndex++;
		}

		while ( strlen( $varString ) <= $ssize ) {
			$varString = str_shuffle( "{$varString}{$varString}" );
			echo strlen( $varString ) . " = {$ssize}";
		}

		return substr( $varString, 0, $ssize );
	}

	/**
	 * Process the special characters and clean the string
	 *
	 * @param string $string : string to be cleaned
	 * @return string : processed string
	 */
	public static function cleanString( $string ) {
		if ( is_numeric( $string ) )
			return $string;

		if ( $string === null || $string == "" )
			return "";

		$string1 = trim( strip_tags( $string ) );

		/* DEBUG $string = mysql_real_escape_string($string); */

		$string2 = htmlspecialchars( $string1, ENT_QUOTES );
		return $string2;
	}

	/**
	 * Convert date from standard date format to string
	 *
	 * @param string $date
	 * @return string
	 */
	public static function convertDate( $date ) {
		$datetime = strtotime( $date );
		return date( "F j, Y", $datetime );
	}

	/**
	 * Format a date expression
	 *
	 * @param $date
	 * @param $format
	 * @return bool|string
	 */
	public static function formatDate( $date, $format ) {
		$datetime = strtotime( $date );
		return date( $format, $datetime );
	}

	/**
	 * Get file size in bytes converted to GB/MB/KB
	 *
	 * @param int $ssize : size in bytes
	 * @return string : file size in GB/MB/KB
	 */
	public static function fileSizeString( $ssize ) {
		if ( $ssize >= 1000 && $ssize < 1000000 )
			$fileSize = ( string ) round( $ssize/1000, 2 ) . " kB";

		elseif ( $ssize >= 1000000 && $ssize < 10000000 )
			$fileSize = ( string ) round( $ssize/1000000, 2 ) . " MB";

		elseif ( $ssize >= 10000000 )
			$fileSize = ( string ) round( $ssize/1000000000, 2 ) . " GB";

		else
			$fileSize = ( string ) round( $ssize ) . " Bytes";

		return $fileSize;
	}

	/**
	 * Check whether a string is a valid email id
	 *
	 * @param string $string : string to be checked
	 * @return bool
	 */
	public static function isValidEmail( $string ) {
		if ( preg_match( "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/",
			$string ) )
			return true;

		else
			return false;
	}

	/**
	 * Check whether a string contains only letters from English alphabet
	 *
	 * @param string $string
	 * @return bool
	 */
	public static function isAplphabet( $string ) {
		$regex = "/^[A-Za-z ]+$/";

		if ( preg_match( $regex, $string ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check whether a string contains only letters and numbers
	 *
	 * @param string $string
	 * @return bool
	 */
	public static function isAlphaNumeric( $string ) {
		$regex = "/^[A-Za-z0-9]+$/";

		if ( preg_match( $regex, $string ) ) {
			return true;
		}

		return false;
	}
}
<?php
/**
 * Helper.php - PHP string functions implemented in methods
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
 * Class Steelcode_String_Helper
 *
 * @category Steelcode
 * @package Steelcode_String
 */
class Steelcode_String_Helper {

	/**
	 * String find and replace
	 *
	 * @param string $search
	 * @param string $replace
	 * @param string $subject
	 * @param int $count
	 *
	 * @return mixed
	 */
	public static function replace( $search, $replace, $subject, &$count=null ) {
		return str_replace( $search, $replace, $subject, $count );
	}

	/**
	 * Split string by delimiter
	 *
	 * @param string $delimiter
	 * @param string $string
	 * @param int $limit
	 *
	 * @return array
	 */
	public static function explode( $delimiter, $string, $limit=1000 ) {
		return explode( $delimiter, $string, $limit );
	}

	/**
	 * Get the position of a substring
	 *
	 * @param string $needle
	 * @param string $haystack
	 * @param int $offset
	 *
	 * @return bool|int
	 */
	public static function position( $needle, $haystack, $offset=0 ) {
		return strpos( $haystack, $needle, $offset );
	}

	/**
	 * Checks whether a string has a substring in it
	 *
	 * @param string $substring
	 * @param string $haystack
	 *
	 * @return bool
	 */
	public static function hasSubSting( $substring, $haystack ) {
		$position = self::position( $substring, $haystack );

		if ( !Steelcode_Types_Helper::isNumeric( $position ) && $position === false ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if a string is empty or null or the word null itself
	 *
	 * @param string $string
	 * @return bool
	 */
	public static function isNull( $string ) {
		if ( $string == "" || $string == "NULL" || $string == "null" ||
			$string == NULL || $string == null )
			return true;

		else
			return false;
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
	 * Find length of a string
	 *
	 * @param string $string
	 * @return int
	 */
	public static function length( $string ) {
		return strlen( $string );
	}

	/**
	 * Get length of string with respect to character encoding
	 *
	 * @param string $string
	 * @return int
	 */
	public static function safeLength( $string ) {
		if ( function_exists( 'mb_strlen' ) ) {
			return mb_strlen($string, '8bit');
		}

		return self::length( $string );
	}

	/**
	 * Find substring from $start to $length
	 *
	 * @param string $string
	 * @param int $start
	 * @param mixed $length
	 *
	 * @return string
	 */
	public static function safeSubString( $string, $start, $length=null ) {
		if ( function_exists( 'mb_substr' ) ) {

			if ( !isset( $length ) ) {
				if ($start >= 0) {
					$length = self::safeLength( $string ) - $start;

				} else {
					$length = -$start;
				}
			}

			return mb_substr( $string, $start, $length, '8bit' );
		}

		if ( isset( $length ) ) {
			return substr( $string, $start, $length );

		} else {
			return substr( $string, $start );
		}
	}
}


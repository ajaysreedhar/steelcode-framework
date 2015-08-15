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
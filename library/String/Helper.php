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
}


<?php
/**
 * Helper.php - PHP array functions implemented in methods
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

namespace Steelcode\Map;

/**
 * Class Helper
 *
 * @category Steelcode
 * @package Steelcode_Map
 */
class Helper {

	/**
	 * Push new items to array
	 *
	 * @param array $array
	 * @param mixed $_
	 */
	public static function push( &$array ) {
		$args = func_get_args();

		foreach ( $args as $key => $value ) {
			if ( $key === 0 )
				continue;

			array_push( $array, $value );
		}
	}

	/**
	 * Check whether a variable is an array or not
	 *
	 * @param mixed $variable
	 * @return bool
	 */
	public static function isArray( $variable ) {
		return is_array( $variable );
	}

	/**
	 * Check whether an array is empty or not
	 *
	 * @param array $array
	 * @return bool
	 */
	public static function isEmpty( array $array ) {
		return ( self::isArray( $array ) && empty( $array ) );
	}

	/**
	 * Implode an array with the given glue string
	 *
	 * @param string $glue
	 * @param array $array
	 *
	 * @return string
	 */
	public static function implode( $glue, array $array ) {
		$string = "";
		$length = count( $array );

		foreach ( $array as $value ) {
			$length--;

			$string = ( $length === 0 ) ? "{$string}{$value}" : "{$string}{$value}{$glue}";
		}

		return $string;
	}

	/**
	 * Implode an array with the given glue string
	 *
	 * @param string $glue
	 * @param array $array
	 *
	 * @return string
	 */
	public static function implodeWithKey( $glue, array $array ) {
		$string = "";
		$length = count( $array );

		foreach ( $array as $key=>$value ) {
			$length--;

			$string = ( $length === 0 ) ? "{$string}{$key}={$value}" : "{$string}{$key}={$value}{$glue}";
		}

		return $string;
	}

	/**
	 * Get the elements in an array indexed by integers in ascending order
	 *
	 * @param array $array
	 * @return array
	 */
	public static function values( array $array ) {
		$vArray = array();

		foreach ( $array as $value ) {
			$vArray[] = $value;
		}

		return $vArray;
	}

	/**
	 * Check whether an array has a specific key
	 *
	 * @param mixed $key
	 * @param array $array
	 * @param bool $strict
	 *
	 * @return bool
	 */
	public static function hasKey( $key, $array, $strict=true ) {
		foreach ( $array as $index => $value ) {
			if ( $strict ? ( $index === $key ) : ( $index == $key ) ) {
				return true;
			}
		}

		return false;
	}
}


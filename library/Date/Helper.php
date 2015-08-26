<?php
/**
 * Helper.php - Date helper functions
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
 * Class Steelcode_Date_Helper
 *
 * @category Steelcode
 * @package Steelcode_Date
 */
class Steelcode_Date_Helper {

	/**
	 * Get current unix time
	 *
	 * @return int
	 */
	public static function time() {
		return time();
	}

	/**
	 * Format local date/time
	 *
	 * @param string $format
	 * @param int $timestamp
	 *
	 * @return string
	 */
	public static function date( $format, $timestamp=0 ) {
		return date( $format, $timestamp === 0 ? time() : $timestamp );
	}

	/**
	 * Get unix time
	 *
	 * @param string $time
	 * @param int $now
	 *
	 * @return int
	 */
	public static function unixTime( $time, $now=0 ) {
		if ( $now === 0 )
			$now = self::time();

		return strtotime( $time, $now );
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
	 * Check date of format yyyy-mm-dd is valid
	 *
	 * @param string $date
	 * @return bool
	 */
	public static function isValidDate( $date ) {
		if ( empty( $date ) ) {
			return false;
		}

		$segments = Steelcode_String_Helper::explode( '-', $date );

		if ( count( $segments ) != 3 ) {
			return false;
		}

		return checkdate( $segments[1], $segments[2], $segments[0] );
	}
}

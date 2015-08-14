<?php
/**
 * Expression.php - Contains miscellaneous query expressions
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
 * Class Steelcode_Database_Expression
 *
 * Contains miscellaneous query expressions.
 * This is an abstract class and hence objects can not be created
 *
 * @category Steelcode
 * @package Steelcode_Database
 */
abstract class Steelcode_Database_Expression {

	/**
	 * Get the date expression from a date string
	 *
	 * @param string $date
	 * @return string
	 */
	public static function dateExpression( $date ) {
		$datetime = strtotime( $date );
		return date( 'Y-m-d', $datetime );
	}

	/**
	 * Get the current datetime expression
	 *
	 * This function works well iff default time zone
	 * is set in bootstrap file
	 *
	 * @return string
	 */
	public static function currentDateTime() {
		return date( 'Y-m-d H:i:s' );
	}

	/**
	 * Get the current date expression
	 *
	 * This function works well iff default time zone
	 * is set in bootstrap file
	 *
	 * @return string
	 */
	public static function currentDate() {
		return date( 'Y-m-d' );
	}

	/**
	 * NOW()
	 *
	 * @return string
	 */
	public static function now() {
		return self::currentDateTime();
	}
}

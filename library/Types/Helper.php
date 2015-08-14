<?php
/**
 * Helper.php - Datatypes helper
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
 * Class Steelcode_Types_Helper
 *
 * @category Steelcode
 * @package Steelcode_Types
 */
class Steelcode_Types_Helper {

	/**
	 * Check whether a variable contains numeric values
	 *
	 * @param mixed $variable
	 * @return bool
	 */
	public static function isNumeric( $variable ) {
		return is_numeric( $variable );
	}
}

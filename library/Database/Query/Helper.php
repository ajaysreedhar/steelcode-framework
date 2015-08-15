<?php
/**
 * Helper.php - Steelcode database query helper
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
 * Class Steelcode_Database_Query_Helper
 *
 * @category Steelcode
 * @package Steelcode_Database
 */
class Steelcode_Database_Query_Helper {

	/**
	 * Prepare column names for query
	 *
	 * @param array $columns
	 * @return string
	 */
	public static function columns( array $columns ) {
		$columnValue = "";

		foreach ( $columns as $key => $value ) {
			if ( is_numeric( $key ) ) {
				$columnValue = "";

			} else {
				$columnValue = "{$key}.";
			}

			if ( Steelcode_String_Helper::hasSubSting( ' ', $value ) ) {
				$columnValue .= $value;

			} else {
				$columnValue = "`{$columnValue}{$value}`";
			}

			$columns[$key] = $columnValue;
		}

		return Steelcode_Array_Helper::implode( ', ', $columns );
	}
}

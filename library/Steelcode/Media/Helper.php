<?php
/**
 * Helper.php - Steelcode media helper
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
 * Class Steelcode_Media_Helper
 *
 * @category Steelcode
 * @package Steelcode_Media
 */
abstract class Steelcode_Media_Helper {

	/**
	 * Find the extension of a file from its filename and
	 * get it in lower case
	 *
	 * @param string $file : name of the file
	 * @return string : extension of the file in lower case
	 */
	public static function fileExtension( $file ) {
		if ( Steelcode_String_Helper::isNull( $file ) ) {
			return null;

		} else {
			$ext = end( explode( '.', $file ) );
			return strtolower( $ext );
		}
	}

	/**
	 * Scan the folder specified in $location
	 * and get the list of files.
	 *
	 * @param string $location
	 * @return array
	 */
	public function scanLocation( $location ) {
		return scandir( $location, 1 );
	}
}

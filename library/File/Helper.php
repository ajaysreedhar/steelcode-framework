<?php
/**
 * Helper.php - File operations helper
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
 * Class Steelcode_File_Helper
 *
 * @category Steelcode
 * @package Steelcode_File
 */
class Steelcode_File_Helper {

	/**
	 * Checks whether a file exists or not
	 *
	 * @param string $file
	 * @return bool
	 */
	public static function exists( $file ) {
		return file_exists( $file );
	}

	/**
	 * Get file size in bytes converted to GB/MB/KB
	 *
	 * @param int $ssize : size in bytes
	 * @return string : file size in GB/MB/KB
	 */
	public static function sizeString( $ssize ) {
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
}

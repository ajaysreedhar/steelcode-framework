<?php
/**
 * Vars.php - Handles $_SERVER variable
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
 * Class Steelcode_Server_Vars
 *
 * @category Steelcode
 * @package Steelcode_Server
 */
class Steelcode_Server_Vars {

	/**
	 * Check whether the server and client are running in the same machine
	 *
	 * @return bool
	 */
	public static function isLocalhost() {
		if ( $_SERVER['HTTP_HOST'] === 'localhost' ||
			$_SERVER['HTTP_HOST'] === '127.0.0.1' ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the server protocol name and version
	 *
	 * @return string
	 */
	public static function protocol() {
		return $_SERVER['SERVER_PROTOCOL'];
	}

	/**
	 * Get values from $_SERVER variable
	 *
	 * @param string $var
	 * @return string
	 */
	public static function getVar( $var ) {
		return ( isset( $_SERVER[$var] ) ? $_SERVER[$var] : null );
	}
}


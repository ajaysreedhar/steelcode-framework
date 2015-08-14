<?php
/**
 * Helper.php - Steelcode application helper methods
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
 * Class Steelcode_Application_Helper
 *
 * @category Steelcode
 * @package Steelcode_Application
 */
class Steelcode_Application_Helper {
	/**
	 * Get the current host url
	 *
	 * @return string
	 */
	public static function host() {
		$host = str_replace( '/', '', Steelcode_Server_Vars::getVar( 'HTTP_HOST' ) );
		return ( Steelcode_SSL_Helper::isSSL() ) ? "https://{$host}" : "http://{$host}";
	}

	/**
	 * Get the current script url without the script name
	 *
	 * @return string
	 */
	public static function url() {
		$hostUrl = self::host();

		if ( ! empty( $scriptName = Steelcode_Server_Vars::getVar( 'SCRIPT_NAME' ) ) ) {
			$arr    = explode( '/', $scriptName );
			$arrLen = count( $arr );
			if ( $arrLen <= 2 ) {
				return $hostUrl;

			} else {
				if ( Steelcode_String_Helper::isNull( $arr[0] ) )
					unset( $arr[0] );

				unset( $arr[$arrLen - 1] );

				$newUrl = implode( '/', $arr );
				return "{$hostUrl}/{$newUrl}";
			}
		}
		return $hostUrl;
	}
}

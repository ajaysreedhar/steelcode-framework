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

namespace Steelcode\Application;

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
		return ( Steelcode_Ssl_Helper::isSSL() ) ? "https://{$host}" : "http://{$host}";
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

	/**
	 * Get url built from controller name and view name
	 *
	 * @param string $domain
	 * @param string $controller
	 * @param array $args
	 *
	 * @return string : the url
	 */
	public static function buildUrl( $domain='index', $controller='index', array $args=null ) {
		$url = self::url();
		$appendArgs = '';

		if ( $args !== null && is_array( $args ) ) {
			foreach ( $args as $getKey => $getValue) {
				if ( is_numeric( $getKey ) )
					$getArg = "#{$getValue}";
				else
					$getArg = "&{$getKey}={$getValue}";

				$appendArgs .= $getArg;
			}

			$appendArgs = "?{$appendArgs}";
			$appendArgs = str_replace( '?&', '?', $appendArgs );
		}

		if ( $domain == 'index' && $controller == 'index' ) {
			return "{$url}{$appendArgs}";

		} elseif( $domain === "index" && $controller != "index" ) {
			return "{$url}/{$controller}{$appendArgs}";
			
		} elseif( $controller == 'index' ) {
			return "{$url}/{$domain}/{$appendArgs}";
		}

		return "{$url}/{$domain}/{$controller}{$appendArgs}";
	}
}


<?php
/**
 * Methods.php - Steelcode HTTP request methods
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
 * Class Steelcode_Http_Request_Methods
 *
 * @category SteelCode
 * @package Steelcode_Http
 */
class Steelcode_Http_Request_Methods {

	/**
	 * Flag to set whether prefix is to be used or not
	 *
	 * @var bool
	 */
	private $_usePrefix = false;

	/**
	 * Request parameter prefix
	 *
	 * @var string
	 */
	private $_prefix = "";

	/**
	 * Create index with prefix
	 *
	 * @param string $index
	 * @return string
	 */
	private function _index( $index ) {
		return ( ( $this->_usePrefix === true ) ? "{$this->_prefix}_{$index}" : "{$index}" );
	}

	/**
	 * Get $_GET value
	 *
	 * @param string $parameter
	 * @return mixed
	 */
	public function get( $parameter ) {
		$index = $this->_index( $parameter );
		return ( isset( $_GET[$index] ) ? $_GET[$index] : null );
	}

	/**
	 * Get $_POST value
	 *
	 * @param string $parameter
	 * @return mixed
	 */
	public function post( $parameter ) {
		$index = $this->_index( $parameter );
		return ( isset( $_POST[$index] ) ? $_POST[$index] : null );
	}
}

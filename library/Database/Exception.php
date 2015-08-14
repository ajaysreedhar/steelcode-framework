<?php
/**
 * Exception.php - Steelcode database exception
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
 * Class Steelcode_Database_Exception
 *
 * @category Steelcode
 * @package Steelcode_Database
 */
class Steelcode_Database_Exception extends Steelcode_Exception {

	/**
	 * Database error code
	 *
	 * @var int
	 */
	private $_dbCode = 0;

	/**
	 * Class constructor
	 *
	 * @param string $message
	 * @param int $dbCode
	 * @param int $code
	 */
	public function __construct( $message = "", $dbCode = 0, $code = 0 ) {
		parent::__construct( $message, (int) $code );
		$this->_dbCode = $dbCode;
	}

	/**
	 * Database error code getter
	 *
	 * @return int
	 */
	public function getDbCode() {
		return $this->_dbCode;
	}
}

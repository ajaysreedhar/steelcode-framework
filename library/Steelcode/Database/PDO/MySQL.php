<?php
/**
 * MySQL.php - Steelcode database PDO MySQL class
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
 * Class Steelcode_Database_PDO_MySQL
 *
 * @category Steelcode
 * @package Steelcode_Database
 */
class Steelcode_Database_PDO_MySQL extends Steelcode_Database_PDO {

	/**
	 * Class constructor
	 * Overrides Steelcode_Database_Adapter_Abstract::__construct();
	 *
	 * @param array|Config_Database $config
	 * @throws Steelcode_Database_Exception
	 */
	public function __construct( $config ) {

		try {
			self::setDriver( 'mysql' );
			parent::__construct( $config );

		} catch ( Steelcode_Database_Exception $ex ) {

			throw $ex;
		}

	}
}

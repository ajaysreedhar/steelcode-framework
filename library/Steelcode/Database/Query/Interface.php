<?php
/**
 * Interface.php - Steelcode database query interface
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
 * Interface Steelcode_Database_Query_Interface
 *
 * @category Steelcode
 * @package Steelcode_Database
 */
interface Steelcode_Database_Query_Interface {

	/**
	 * Creates SELECT query according to the supplied parameters.
	 *
	 * @param string $table
	 * @param string $columns
	 * @param array $where
	 * @return string
	 */
	public function fetchQuery( $table, $columns='*', $where=null );

	/**
	 * Creates INSERT query according to the supplied parameters.
	 *
	 * @param string $table
	 * @param array $bind
	 * @return string
	 */
	public function insertQuery( $table, array $bind );

	/**
	 * Creates UPDATE query according to the supplied parameters.
	 *
	 * @param string $table
	 * @param array $bind
	 * @param array $where
	 * @return string
	 */
	public function updateQuery( $table, array $bind, $where=null );

	/**
	 * Creates DELETE query according to the supplied parameters.
	 *
	 * @param string $table
	 * @param array $where
	 * @return string
	 */
	public function deleteQuery( $table, $where=null );
}

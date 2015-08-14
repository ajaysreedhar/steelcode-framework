<?php
/**
 * Builder.php - Steelcode database query builder
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
 * Class Steelcode_Database_Query_Builder
 *
 * @category Steelcode
 * @package Steelcode_Database
 */
class Steelcode_Database_Query_Builder
	extends Steelcode_Database_Query_Abstract
	implements Steelcode_Database_Query_Interface {

	/**
	 * Prepare the placeholder for column values
	 *
	 * @param string $indexName
	 *
	 * @return string
	 */
	private function _placeholder( $indexName ) {
		return ":{$indexName}";
	}

	/**
	 * Prepare where condition from strings or array
	 *
	 * @param array|string $where : where condition as array or string
	 * @return string
	 */
	private function _where( $where=null ) {
		if ( $where === null )
			return '0';
		elseif ( is_string( $where ) || is_numeric( $where ) )
			return $where;
		elseif ( !is_array( $where ) )
			return '0';

		$arrayWhere = array();

		foreach ( $where as $key ) {
			$condition = "`{$key}`=" . $this->_placeholder( $key );

			array_push( $arrayWhere, $condition );
		}

		return implode( ' AND ', $arrayWhere );
	}

	/**
	 * Creates SELECT query according to the supplied parameters.
	 *
	 * @param string $table
	 * @param array|string $columns
	 * @param array $where
	 * @return string
	 */
	public function fetchQuery( $table, $columns='*', $where=null ) {
		$tableName = $this->table( $table );

		$this->_query = "SELECT " . $this->_colNames( $columns )
			. " FROM `{$tableName}` WHERE " . $this->_where( $where );

		return $this->_query;
	}

	/**
	 * Creates INSERT query according to the supplied parameters.
	 *
	 * @param string $table
	 * @param array $bind
	 * @return string
	 */
	public function insertQuery( $table, array $bind ) {
		$tableName = $this->table( $table );

		$strAttr   = '(';
		$strValues = '(';

		$totalCount = count( $bind );
		$indexCount = 1;

		foreach ( $bind as $attrName ) {
			if ( $totalCount == $indexCount ) {
				$strAttr .= "`{$attrName}`)";

				$tempString  = $this->_placeholder( $attrName ) . ")";
				$strValues  .= $tempString;

			} else {
				$strAttr 	.= "`{$attrName}`, ";

				$tempString  = $this->_placeholder( $attrName ) . ", ";
				$strValues	.= $tempString;
			}

			$indexCount++;
		}

		$this->_query = "INSERT INTO `{$tableName}` {$strAttr} VALUES {$strValues}";

		return $this->_query;
	}

	/**
	 * Creates UPDATE query according to the supplied parameters.
	 *
	 * @param string $table
	 * @param array $bind
	 * @param array $where
	 * @return string
	 */
	public function updateQuery( $table, array $bind, $where=null ) {
		$tableName = $this->table( $table );

		$setList = ''; /* set list is initially empty */

		$totalCount = count( $bind ); /* get the total count of attributes */
		$indexCount = 1;                   /* current attribute index */

		/* get each attribute name and attribute value */
		foreach ( $bind as $attrName ) {
			if ( $totalCount == $indexCount ) {
				$strAttr 	= "`{$attrName}`";
				$strValues	= $this->_placeholder( $attrName );

			} else {
				$strAttr 	= "`{$attrName}`";

				$tempString = $this->_placeholder( $attrName ) . ", " ;
				$strValues	= $tempString;

			}

			$setList .= "{$strAttr}=$strValues";
			$indexCount++;
		}

		$this->_query = "UPDATE `{$tableName}` SET {$setList} WHERE " . $this->_where( $where );

		return $this->_query;
	}

	/**
	 * Creates DELETE query according to the supplied parameters.
	 *
	 * @param string $table
	 * @param array $where
	 * @return string
	 */
	public function deleteQuery( $table, $where=null ) {
		$tableName = $this->table( $table );
		$this->_query = "DELETE FROM `{$tableName}` WHERE " . $this->_where( $where );

		return $this->_query;
	}
}

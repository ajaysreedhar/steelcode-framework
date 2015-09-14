<?php
/**
 * Abstract.php - Steelcode database query abstract
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
 * Class Steelcode_Database_Query_Abstract
 *
 * @category Steelcode
 * @package Steelcode_Database
 */
abstract class Steelcode_Database_Query_Abstract {

	/**
	 * Flag to set whether table prefix is to be used or not
	 *
	 * @var bool
	 */
	private $_usePrefix = true;

	/**
	 * Steelcode table prefix
	 *
	 * @var string
	 */
	private $_tPrefix;

	/**
	 * Last created query
	 *
	 * @var string
	 */
	protected $_query;

	/**
	 * Prepare column names from an array or string
	 *
	 * @param array|string $columns
	 * @return string
	 */
	protected function _colNames( $columns = '*' ) {
		if ( !is_array( $columns ) )
			return $columns;

		return '`' . implode( '`, `' , $columns ) . '`';
	}

	/**
	 * Class constructor
	 * Sets table prefix and enables prefix usage if $tPrefix is supplied
	 *
	 * @param string $tPrefix
	 */
	public function __construct( $tPrefix=null ) {

		if ( !empty( $tPrefix ) ) {
			$this->setPrefix( $tPrefix );
			$this->enablePrefix();
		}
	}

	/**
	 * Enable table prefix usage
	 */
	public function enablePrefix() {
		$this->_usePrefix = true;
	}

	/**
	 * Disable table prefix usage
	 */
	public function disablePrefix() {
		$this->_usePrefix = false;
	}

	/**
	 * Appends table name at the end of table prefix
	 *
	 * @param string $table : table name
	 * @return string : table name with its prefix
	 */
	public function table( $table ) {
		return ( $this->_usePrefix ) ? $this->_tPrefix . '_' . $table : $table;
	}

	/**
	 * Get a string in single quotes
	 *
	 * @param string $string : the string to be quoted
	 * @return string
	 */
	public function getInQuotes( $string ) {
		if ( is_numeric( $string ) )
			return $string;

		return "'{$string}'";
	}

	/**
	 * Creates CREATE TABLE query according to the supplied parameters
	 *
	 * @param string $table : name of the table
	 * @param array $bind : array of attributes to bind
	 *
	 * @return int
	 */
	public function createQuery( $table, array $bind ) {
		$tableName = $this->table( $table );

		$subKey = '(';

		$arrayLength = count( $bind );
		$indexCount = 1;

		foreach ( $bind as $key => $value ) {

			if ( $indexCount == $arrayLength ) {
				$subKey .= "`{$key}` {$value})" ;

			} else {
				$subKey .= "`{$key}` {$value}, " ;
			}

			$indexCount++;
		}

		$this->_query = "CREATE TABLE IF NOT EXISTS `{$tableName}` {$subKey}";

		return $this->_query;
	}

	/**
	 * Creates ALTER TABLE query to add foreign key constraint,
	 * according to the supplied parameters
	 *
	 * @param string $foreign
	 * @param string $primary
	 * @param string $onUpdate
	 * @param string $onDelete
	 * @return string
	 */
	public function foreignRelationQuery( $foreign, $primary,
								   $onUpdate='CASCADE', $onDelete='CASCADE' ) {

		$expPrimary = explode( '.', $primary );
		$expForeign = explode( '.', $foreign );

		$parentTable = $this->table( $expPrimary[0] );
		$primaryAttr = $expPrimary[1];

		$childTable  = $this->table( $expForeign[0] );
		$foreignAttr = $expForeign[1];

		$onChange = '';

		if ( is_array( $onUpdate ) ) {
			foreach ( $onUpdate as $key => $value ) {
				if ( $key == 'delete' || $key == 0 )
					$onChange .= " ON DELETE {$value}";

				elseif ( $key == 'update' || $key == 1 )
					$onChange .= " ON UPDATE {$value}";
			}

		} else {
			$onChange = " ON DELETE {$onDelete} ON UPDATE {$onUpdate}";
		}

		$this->_query = "ALTER TABLE `{$childTable}` ADD FOREIGN KEY(`{$foreignAttr}`)"
			. " REFERENCES `{$parentTable}`(`{$primaryAttr}`){$onChange}";

		return $this->_query;
	}

	/**
	 * Set table prefix
	 *
	 * @param string $prefix
	 */
	public function setPrefix( $prefix ) {
		$this->_tPrefix = $prefix;
	}

	/**
	 * Creates SELECT query according to the supplied parameters.
	 * This method must be defined in the child class.
	 *
	 * @param string $table
	 * @param string $columns
	 * @param array $where
	 * @return mixed
	 */
	abstract public function fetchQuery( $table, $columns='*', $where=null );

	/**
	 * Creates INSERT query according to the supplied parameters.
	 * This method must be defined in the child class.
	 *
	 * @param string $table
	 * @param array $bind
	 * @return mixed
	 */
	abstract public function insertQuery( $table, array $bind );

	/**
	 * Creates UPDATE query according to the supplied parameters.
	 * This method must be defined in the child class.
	 *
	 * @param string $table
	 * @param array $bind
	 * @param array $where
	 * @return mixed
	 */
	abstract public function updateQuery( $table, array $bind, $where=null );

	/**
	 * Creates DELETE query according to the supplied parameters.
	 * This method must be defined in the child class.
	 *
	 * @param string $table
	 * @param array $where
	 * @return mixed
	 */
	abstract public function deleteQuery( $table, $where=null );
}

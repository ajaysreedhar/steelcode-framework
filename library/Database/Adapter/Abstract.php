<?php
/**
 * Abstract.php - Steelcode database adapter abstract class
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
 * Class Steelcode_Database_Adapter_Abstract
 *
 * @category Steelcode
 * @package Steelcode_Database
 */
abstract class Steelcode_Database_Adapter_Abstract {

	const ROW_COUNT = 1;	/* Return an integer indicating number of rows affected */

	const HANDLE = 2;		/* Return the PDOStatement object after executing the query */

	const ONE_ROW = 3;		/* Return one row as an array after executing the query */

	const ALL_ROWS = 4;	/* Return all rows as an array of arrays after executing the query */

	/**
	 * Fetch mode supplied to *Statement::fetch()
	 * and *Statement::fetchAll() methods
	 *
	 * @var int
	 */
	private $_fetchMode=0;

	/**
	 * Number of rows affected by last query
	 *
	 * @var int
	 */
	protected $_rowCount = 0;

	/**
	 * SQLSTATE error code
	 *
	 * @var string
	 */
	protected $_errorCode = 'NONE';

	/**
	 * Database username
	 *
	 * @var string
	 */
	protected $_dbLogin;

	/**
	 * Database password
	 *
	 * @var string
	 */
	protected $_dbPassword = '';

	/**
	 * Database host
	 *
	 * @var string
	 */
	protected $_dbHost;

	/**
	 * Database name
	 *
	 * @var string
	 */
	protected $_dbName;

	/**
	 * Last query created
	 *
	 * @var string
	 */
	protected $_query;

	/**
	 * Database connection errors
	 *
	 * @var string
	 */
	protected $_errorMessage = '';

	/**
	 * Object of PDO
	 *
	 * @var PDO
	 */
	protected $_dbHandle;

	/**
	 * Object of query builder
	 *
	 * @var Steelcode_Database_Query_Abstract
	 */
	protected $_sqlBuilder;

	/**
	 * Sets error message
	 *
	 * @param string|array $message
	 */
	protected function _setErrorMessage( $message, $code=0 ) {
		if ( is_array( $message ) && $message != null ) {
			$this->_errorMessage = $message[2];
			$this->_errorCode    = $message[0];

		} else {
			$this->_errorMessage = $message;
			$this->_errorCode = $code;
		}
	}

	/**
	 * Sets number of rows affected by last query
	 *
	 * @param int $count
	 */
	protected function _setRowCount( $count ) {
		$this->_rowCount = $count;
	}

	/**
	 * Class constructor
	 *
	 * @param array|Config_Database $config
	 */
	public function __construct( $config ) {

		if ( $config instanceof Config_Database ) {
			$dbParams = $config->toArray();

		} elseif ( is_array( $config ) ) {
			$dbParams = $config;

		} else {
			$dbParams = json_decode( $config );
		}

		$this->_dbLogin    = $dbParams['user'];
		$this->_dbPassword = $dbParams['password'];
		$this->_dbHost     = $dbParams['host'];
		$this->_dbName     = $dbParams['database'];

		$this->setPrefix( $dbParams['prefix'] );
	}

	/**
	 * Creates a table in the database with attributes given
	 *
	 * @param string $table : name of the table
	 * @param array $bind : array of attributes to bind
	 *
	 * @return int
	 * @throws Steelcode_Database_Exception
	 */
	public function create( $table, array $bind ) {
		if ( $bind === null ) {
			$this->_setErrorMessage( array( 'SC002', 0, 'Invalid attribute names and values provided' ) );
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}

		$this->_query = $this->_sqlBuilder->createQuery( $table, $bind );

		if ( null === $this->_execute() ) {
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}
	}

	/**
	 * Apply foreign key constraint to the table
	 *
	 * @param string $table : name of the table
	 * @param string $references : referenced table
	 * @param array|string $onUpdate (optional) : a value for ON UPDATE
	 * @param string $onDelete (optional) : can be array or string
	 *
	 * @throws Steelcode_Database_Exception
	 *
	 * @return int
	 */
	public function applyForeignKey( $table, $references,
									 $onUpdate='CASCADE', $onDelete='CASCADE' ) {

		$this->_query = $this->_sqlBuilder->foreignRelationQuery( $table, $references, $onUpdate, $onDelete );

		if ( null === $this->_execute() ) {
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}
	}

	/**
	 * Execute a hardcoded query with or without parameters
	 *
	 * @param string $query
	 * @param array $bind
	 *
	 * @throws Steelcode_Database_Exception
	 */
	public function execute( $query, array $bind=null ) {
		$this->_query = $query;

		if ( null === $this->_execute( $bind ) ) {
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}
	}

	/**
	 * Execute custom prepared query and get the PDOStatement handle
	 *
	 * @param string $query
	 * @param array $bind
	 * @return PDOStatement
	 */
	public function query( $query, array $bind=null ) {
		$this->_query = $query;
		return $this->_execute( $bind );
	}

	/**
	 * Execute custom prepared query and get first row
	 *
	 * @param string $query
	 * @param array $bind
	 * @return array
	 */
	public function queryOneRow( $query, array $bind=null ) {
		$this->_query = $query;
		$sth = $this->_execute( $bind );

		if ( $sth === null ) {
			return $sth;
		}

		return $sth->fetch( $this->_fetchMode );
	}

	/**
	 * Execute custom prepared query and get all rows
	 *
	 * @param string $query
	 * @param array $bind
	 * @return array
	 */
	public function queryAllRows( $query, array $bind=null ) {
		$this->_query = $query;
		$sth = $this->_execute( $bind );

		if ( $sth === null ) {
			return $sth;
		}

		return $sth->fetchAll( $this->_fetchMode );
	}

	/**
	 * Fetch one row from a table in the database
	 *
	 * @param string $table
	 * @param string $columns
	 * @param array $where
	 *
	 * @return array|null
	 */
	public function fetchOneRow( $table, $columns='*', $where=null ) {
		$whereKeys = ( $where !== null && is_array( $where ) ) ?
			array_keys( $where ) : $where;

		$query = $this->_sqlBuilder->fetchQuery( $table, $columns, $whereKeys );
		$bind = ( is_array( $where ) ) ? $where : null;

		return $this->queryOneRow( $query, $bind );
	}

	/**
	 * Fetch all rows from a table in the database
	 *
	 * @param string $table
	 * @param string $columns
	 * @param array $where
	 *
	 * @return array
	 */
	public function fetchAllRows( $table, $columns='*', $where=null ) {
		$whereKeys = ( $where !== null && is_array( $where ) ) ?
			array_keys( $where ) : $where;

		$query = $this->_sqlBuilder->fetchQuery( $table, $columns, $whereKeys );
		$bind = ( is_array( $where ) ) ? $where : null;

		return $this->queryAllRows( $query, $bind );
	}

	/**
	 * Get the count of rows from a table
	 *
	 * @param string $table
	 * @param string $attribute
	 * @param array $where
	 * @return int
	 */
	public function fetchCount( $table, $attribute, $where=null ) {
		$result = $this->fetchOneRow( $table, "COUNT(`{$attribute}`) AS total", $where );

		if ( is_array( $result ) && isset( $result['total'] ) ) {
			return $result['total'];
		}

		return -1;
	}

	/**
	 * Insert a row into a table in the database
	 *
	 * @param string $table : name of the table without prefix
	 * @param array $bind : array of attribute names and values
	 *
	 * @throws Steelcode_Database_Exception
	 */
	public function insert( $table, array $bind ) {
		if ( $bind === null ) {
			$this->_setErrorMessage( array( 'SC002', 0, 'Invalid attribute names and values provided' ) );
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}

		$this->_query = $this->_sqlBuilder->insertQuery( $table, array_keys( $bind ) );

		if ( null === $this->_execute( $bind ) ) {
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}
	}

	/**
	 * Update a row in a table in the database
	 *
	 * @param string $table : name of table to be updated
	 * @param array $bind : array of attributes
	 * @param array $where : where attribute
	 *
	 * @throws Steelcode_Database_Exception
	 */
	public function update( $table, array $bind, $where=null ) {
		$whereKeys = ( $where !== null && is_array( $where ) ) ?
			array_keys( $where ) : $where;

		$this->_query = $this->_sqlBuilder->updateQuery( $table, array_keys( $bind ), $whereKeys );
		$arrayUpdate  = ( $where !== null && is_array( $where ) ) ? array_merge( $bind, $where ) : $bind;

		if ( null === $this->_execute( $arrayUpdate ) ) {
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}
	}

	/**
	 * Deletes a row in a table in the database
	 *
	 * @param string $table : name of the table
	 * @param array $where : where attribute
	 *
	 * @throws Steelcode_Database_Exception
	 */
	public function delete( $table, $where=null ) {
		$whereKeys = ( $where !== null && is_array( $where ) ) ?
			array_keys( $where ) : $where;

		$bind = ( is_array( $where ) ) ? $where : null;

		$this->_query = $this->_sqlBuilder->deleteQuery( $table, $whereKeys );

		if ( null === $this->_execute( $bind ) ) {
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}
	}

	/**
	 * Enable table prefix in table table names
	 */
	public function enablePrefix() {
		$this->_sqlBuilder->enablePrefix();
	}

	/**
	 * Disable table prefix in table names
	 */
	public function disablePrefix() {
		$this->_sqlBuilder->disablePrefix();
	}

	/**
	 * Set table prefix
	 *
	 * @param string $prefix
	 */
	public function setPrefix( $prefix ) {
		if ( $this->_sqlBuilder instanceof Steelcode_Database_Query_Abstract )
			$this->_sqlBuilder->setPrefix( $prefix );
	}

	/**
	 * Get the table name prepended by table prefix
	 *
	 * @param string $table
	 * @return string
	 */
	public function table( $table ) {
		return $this->_sqlBuilder->table( $table );
	}

	/**
	 * SQL builder setter
	 * @param \Steelcode_Database_Query_Abstract $sqlBuilder
	 */
	public function setSqlBuilder( Steelcode_Database_Query_Abstract $sqlBuilder ) {
		$this->_sqlBuilder = $sqlBuilder;
	}

	/**
	 * Set fetch mode
	 *
	 * @param int $mode
	 */
	public function setFetchMode( $mode ) {
		$this->_fetchMode = $mode;
	}

	/**
	 * Last query getter
	 *
	 * @return string
	 */
	public function getQuery() {
		return $this->_query;
	}

	/**
	 * Affected rows count getter
	 *
	 * @return int
	 */
	public function getRowCount() {
		return $this->_rowCount;
	}

	/**
	 * Database error description getter
	 *
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->_errorMessage;
	}

	/**
	 * SQL builder getter
	 *
	 * @return \Steelcode_Database_Query_Builder
	 */
	public function getSqlBuilder() {
		return $this->_sqlBuilder;
	}


	/**
	 * Creates database handler and connects to database.
	 * Must be defined in child class.
	 *
	 * @return bool
	 */
	abstract protected function _connect();

	/**
	 * Prepares a statement and executes it.
	 * Must be defined in child class
	 *
	 * @param array $bind
	 * @return mixed
	 */
	abstract protected function _execute( array $bind=null );
}
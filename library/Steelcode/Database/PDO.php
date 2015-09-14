 <?php
/**
 * PDO.php - Steelcode database PDO
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
 * Class Steelcode_Database_PDO
 *
 * @category Steelcode
 * @package Steelcode_Database
 */
class Steelcode_Database_PDO extends Steelcode_Database_Adapter_Abstract {

	/**
	 * Last insert Id
	 *
	 * @var string
	 */
	private $_insertId = '';

	/**
	 * Database connection driver name
	 *
	 * @var string
	 */
	private $_dbDriver = 'mysql';

	/**
	 * Create DSN string required for PDO class
	 *
	 * @return string
	 */
	private function _dsn() {
		return "{$this->_dbDriver}:host={$this->_dbHost};dbname={$this->_dbName}";
	}

	/**
	 * Creates a PDO object and connects to database
	 *
	 * @return bool
	 */
	protected function _connect() {
		try {
			$this->_dbHandle = new PDO( $this->_dsn(), $this->_dbLogin, $this->_dbPassword );

		} catch ( PDOException $ex ) {
			$this->_dbHandle = null;
			$this->_setErrorMessage( $ex->getMessage() );

			return false;
		}

		return true;
	}

	/**
	 * Prepares and executes a query with bind parameters, if any
	 *
	 * @param array $bind
	 * @return mixed
	 * @throws Steelcode_Database_Exception
	 */
	protected function _execute( array $bind=null ) {
		$stHandle = $this->_dbHandle->prepare( $this->_query );

		if ( !( $stHandle instanceof PDOStatement ) ) {
			$this->_setErrorMessage( $this->_dbHandle->errorInfo() );

			return null;
		}

		if ( false === $stHandle->execute( $bind ) ) {
			$this->_setErrorMessage( $stHandle->errorInfo() );

			return null;
		}

		$this->_insertId = $this->_dbHandle->lastInsertId();
		$this->_setRowCount( $stHandle->rowCount() ) ;

		return $stHandle;
	}

	/**
	 * Class constructor
	 * Overrides Steelcode_Database_Adapter_Abstract::__construct();
	 *
	 * @param array|Config_Database $config
	 * @throws Steelcode_Database_Exception
	 */
	public function __construct( $config ) {
		if ( !extension_loaded( 'PDO' ) ) {
			$this->_setErrorMessage( array( 'SC001', 0, 'The extension PDO is not loaded' ) );
			throw new Steelcode_Database_Exception( $this->getErrorMessage() );
		}

		$this->setSqlBuilder( new Steelcode_Database_Query_Builder() );

		parent::__construct( $config );

		if ( false === $this->_connect() ) {
			throw new Steelcode_Database_Exception( $this->getErrorMessage() );
		}

		$this->setFetchMode( PDO::FETCH_ASSOC );
	}

	/**
	 * Begin transaction and disable autocommit mode
	 *
	 * @throws Steelcode_Database_Exception
	 */
	public function beginTransaction() {
		if ( $this->_dbHandle->beginTransaction() ) {
			return;

		} else {
			$this->_setErrorMessage( $this->_dbHandle->errorInfo() );
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}
	}

	/**
	 * Commit transaction and enable autocommit mode
	 *
	 * @throws Steelcode_Database_Exception
	 */
	public function commitTransaction() {
		if ( $this->_dbHandle->commit() ) {
			return;

		} else {
			$this->_setErrorMessage( $this->_dbHandle->errorInfo() );
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}
	}

	/**
	 * Roll back transaction
	 *
	 * @throws Steelcode_Database_Exception
	 */
	public function rollBackTransaction() {
		if ( $this->_dbHandle->rollBack() ) {
			return;

		} else {
			$this->_setErrorMessage( $this->_dbHandle->errorInfo() );
			throw new Steelcode_Database_Exception( $this->_errorMessage, $this->_errorCode );
		}
	}

	/**
	 * Check whether a transaction is active or not
	 *
	 * @return bool
	 */
	public function isTransactionActive() {
		return $this->_dbHandle->inTransaction();
	}

	/**
	 * Sets PDO database driver
	 *
	 * @param string $driver
	 * @throws Steelcode_Database_Exception
	 */
	public function setDriver($driver) {
		$this->_dbDriver = $driver;
	}

	/**
	 * Get primary key value of last inserted row
	 *
	 * @return string
	 */
	public function getLastInsertId() {
		return $this->_insertId;
	}
}

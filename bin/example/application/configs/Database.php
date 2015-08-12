<?php

/**
 * class Config_Database
 */
class Config_Database {

	/**
	 * Username
	 *
	 * @var string
	 */
	private $_dbLogin = '';

	/**
	 * Password
	 *
	 * @var string
	 */
	private $_dbPassword = '';

	/**
	 * Host
	 *
	 * @var string
	 */
	private $_dbHost = '';

	/**
	 * Database
	 *
	 * @var string
	 */
	private $_dbDatabase = '';

	/**
	 * Table prefix
	 *
	 * @var string
	 */
	private $_dbPrefix = '';

	/**
	 * Returns a JSON encoded string of current configuration
	 *
	 * @return string
	 */
	public function __toString() {
		$config = array (
			'user' => $this->_dbLogin,
			'password' => $this->_dbPassword,
			'host' => $this->_dbHost,
			'database' => $this->_dbDatabase,
			'prefix' => $this->_dbPrefix
			);

		return json_encode( $config );
	}

	/**
	 * Returns an array of configuration indexed by option names
	 *
	 * @return array
	 */
	public function toArray() {
		return array (
			'user' => $this->_dbLogin,
			'password' => $this->_dbPassword,
			'host' => $this->_dbHost,
			'database' => $this->_dbDatabase,
			'prefix' => $this->_dbPrefix
			);
	}
}

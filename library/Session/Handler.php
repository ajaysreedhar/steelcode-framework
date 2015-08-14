<?php
/**
 * Handler.php - Session management utilities
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
 * Class Steelcode_Session_Handler
 *
 * Steelcode session handler class
 * Always use this class to set and unset session variables
 *
 * @category Steelcode
 * @package Steelcode_Session
 */
class Steelcode_Session_Handler {

	/**
	 * Current session Id
	 *
	 * @var string
	 */
	private $_sessionId;

	/**
	 * Salt to be appended before hashing
	 *
	 * @var string
	 */
	private $_salt;

	/**
	 * Hash the session name
	 *
	 * @param string $sessionName
	 * @return string
	 */
	private function _hashSessionName( $sessionName ) {
		$shaSessionName = sha1( $sessionName . $this->_salt );
		$sessionKey     = substr( $shaSessionName, 0, 16 );

		return $sessionKey;
	}

	/**
	 * Class constructor
	 *
	 * @param string $salt : key to be appended before hashing session name
	 */
	public function __construct( $salt = '!St&8*(4)%' ) {
		$this->_salt = $salt;
	}

	/**
	 * Checks whether session is already started
	 *
	 * @return bool
	 */
	public function isSessionStarted() {
		if ( isset( $_SESSION ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Start a new session
	 */
	public function startSession() {
		if ( $this->isSessionStarted() ) {
			$this->_sessionId = session_id();
			return;
		}

		session_start();
		$this->_sessionId = session_id();
	}

	/**
	 * Destroy an active session
	 */
	public function destroySession() {
		session_destroy();
	}

	/**
	 * Regenerates session Id
	 */
	public function regenerateSessionId() {
		session_regenerate_id();

		$this->_sessionId = session_id();
	}

	/**
	 * Register a session after hashing its session name
	 *
	 * @param string $sessionName : actual session name
	 * @param mixed $sessionValue : session value
	 * @throws Steelcode_Session_Exception
	 */
	public function registerSessionValue( $sessionName, $sessionValue ) {
		if ( !$this->isSessionStarted() ) {
			throw new Steelcode_Session_Exception( 'Unable to access session data!' );
		}

		$sessionKey = $this->_hashSessionName( $sessionName );
		$_SESSION[$sessionKey] = $sessionValue;
	}

	/**
	 * Check if a session exist
	 *
	 * @param string $sessionName : actual session name
	 * @throws Steelcode_Session_Exception
	 * @return bool : true if yes, else no
	 */
	public function hasSession( $sessionName ) {
		$sessionKey = $this->_hashSessionName( $sessionName );

		if ( isset( $_SESSION[$sessionKey] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Unset a session variable
	 */
	public function unsetSession( $sessionName ) {
		$sessionKey = $this->_hashSessionName( $sessionName );

		if ( isset( $_SESSION[$sessionKey] ) )
			unset( $_SESSION[$sessionKey] );
	}

	/**
	 * Hash key value setter
	 *
	 * @param string $key : hash key
	 */
	public function setHashKey( $key ) {
		$this->_salt = $key;
	}

	/**
	 * Get the value set in the session with the specified name
	 *
	 * @param string $sessionName : session name
	 * @throws Steelcode_Session_Exception
	 * @return mixed : session value
	 */
	public function getSessionValue( $sessionName ) {
		$sessionKey = $this->_hashSessionName( $sessionName );

		if ( isset( $_SESSION[$sessionKey] ) ) {
			return $_SESSION[$sessionKey];
		}

		throw new Steelcode_Session_Exception( "A session with name {$sessionName} is not registered" );
	}

	/**
	 * Get the hashed session name
	 *
	 * @var string $sessionName : session name
	 * @return string : hashed session key
	 */
	public function getSessionVar( $sessionName ) {
		return $this->_hashSessionName( $sessionName );
	}

	/**
	 * Get session Id
	 *
	 * @return string
	 */
	public function getSessionId() {
		return $this->_sessionId;
	}
}
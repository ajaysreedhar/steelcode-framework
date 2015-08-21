<?php
/**
 * Password.php - Password hashing and verification utilities
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
 * Class Steelcode_Crypto_Hash
 *
 * @category Steelcode
 * @package Steelcode_Crypto
 */
class Steelcode_Crypto_Password {

	/**
	 * A random salt for hashing
	 *
	 * @var string
	 */
	private $_salt = null;

	/**
	 * Loop count for hashing
	 *
	 * @var int
	 */
	private $_loopCount = 1000;

	/**
	 * Class constructor
	 */
	public function __construct( $salt=null ) {
		$this->_salt = null;
	}

	/**
	 * Hash a given password using BlowFish algorithm
	 *
	 * @param string $password
	 * @return string
	 */
	public function hash( $password ) {
		if ( $this->_salt === null ) {
			$randomString = Steelcode_String_Helper::randomString( 10 );
			$randomSalt = substr( sha1( $randomString ), 0, 22 );

		} else {
			$randomSalt = $this->_salt;
		}

		$this->_salt = null;

		$hashCount = $this->_loopCount;

		$hashPassword = "{$password}{$randomSalt}";

		while ( $hashCount >= 0 ) {
			$hashPassword = sha1( sprintf( "%s%s%d", $hashPassword, $randomSalt, $hashCount ) );
			$hashCount--;
		}

		$hashPassword = crypt( $hashPassword, '$2y$12$' . $randomSalt . '$' );
		$hashLength = strlen( $hashPassword );

		return sprintf( "%s%s", $randomSalt, substr( $hashPassword, 29, $hashLength ) );
	}

	/**
	 * Compare a password with a given hash code
	 *
	 * @param string $password
	 * @param string $hashCode
	 * @return bool
	 */
	public function verify( $password, $hashCode ) {
		$this->_salt = substr( $hashCode, 0, 22 );

		$hashPassword = $this->hashPassword( $password );

		if ( $hashPassword === $hashCode )
			return true;

		return false;
	}

	/**
	 * Set loop count for hashing
	 *
	 * @param int $loopCount
	 */
	public function setLoopCount( $loopCount ) {
		$this->_loopCount = $loopCount;
	}
}

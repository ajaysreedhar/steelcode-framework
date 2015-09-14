<?php
/**
 * Hash.php - String hashing and verification utilities
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

class Steelcode_Crypto_Hash {

	/**
	 * A random salt string for hashing
	 *
	 * @var string
	 */
	private $_salt = "";

	/**
	 * Number of times to be hashed
	 * Should be between 1 and 100000
	 *
	 * @var int
	 */
	private $_loops = 1000;

	/**
	 * Class constructor
	 *
	 * @param string $salt
	 */
	public function __construct( $salt=null ) {
		if ( $salt === null )
			$salt = Steelcode_String_Helper::randomString( 32 );

		if ( 32 === Steelcode_String_Helper::safeLength( $salt ) ) {
			$this->_salt = $salt;

		} else {
			throw new Steelcode_Crypto_Exception( 'Salt should be exactly 32 characters long' );
		}
	}

	/**
	 * Hash a string using SHA256 algorithm
	 *
	 * @param string $string
	 * @return string
	 */
	public function stringHash( $string ) {
		$key  = $this->_salt;
		$hash = $string;
		$loop = $this->_loops;

		while ( $loop >= 1 ) {
			$key  = sha1( "{$key}{$hash}" );
			$hash = hash_hmac( 'SHA256', $hash, $key );

			$loop = $loop - 1;
		}

		return $hash;
	}

	/**
	 * Set the loop count
	 *
	 * Throws Steelcode_Crypto_Exception if loopcount is
	 * less than 1 or greater than 100000
	 *
	 * @param int $loops
	 * @throws Steelcode_Crypto_Exception
	 */
	public function setLoops( $loops ) {
		$loops = intval( $loops );

		if ( $loops > 100000 ) {
			throw new Steelcode_Crypto_Exception( 'Higher values of loop count results in perfomance issues' );
		}

		if ( $loops < 1 ) {
			throw new Steelcode_Crypto_Exception( 'Loop count must be atleast 1' );
		}

		$this->_loops = $loops;
	}
}

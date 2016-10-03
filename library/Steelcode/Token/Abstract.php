<?php
/**
 * Abstract.php - Token abstract
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
 * Class Steelcode_Token_Abstract
 *
 * @category Steelcode
 * @package Steelcode_Token
 */
abstract class Steelcode_Token_Abstract {

	/**
	 * Message associated with last operation
	 *
	 * @var string
	 */
	private $_message = "";

	/**
	 * Set a message
	 *
	 * @param $message
	 */
	protected function _setMessage( $message ) {
		$this->_message = $message;
	}

	/**
	 * URL-safe base64 encoding
	 *
	 * @param string $string
	 * @return string
	 */
	public function urlSafeB64Encode( $string ) {
		return str_replace( '=', '', strtr( base64_encode( $string ), '+/', '-_') );
	}

	/**
	 * URL-safe base64 decoding
	 *
	 * @param string $string
	 * @return string
	 */
	public function urlSafeB64Decode( $string ) {
		$mod = strlen( $string ) % 4;

		if ( $mod > 0 ) {
			$padLength = 4 - $mod;
			$string .= str_repeat('=', $padLength);
		}

		return base64_decode( strtr( $string, '-_', '+/' ) );
	}

	/**
	 * Encode the token
	 *
	 * @return string
	 */
	public function encode() {
		return $this->_encode();
	}

	/**
	 * Decode the token
	 *
	 * @param string $token
	 * @return mixed
	 */
	public function decode( $token ) {
		 if ( false === $this->_decode( $token ) ) {
			 throw new Steelcode_Token_Exception ( $this->_message );
		 }
	}

	/**
	 * Get the message associated with last operation
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->_message;
	}

	/**
	 * Must be implemented in child class
	 *
	 * @return string
	 */
	abstract protected function _encode();

	/**
	 * Must be implemented in child class
	 *
	 * @param string $token
	 * @return bool
	 */
	abstract protected function _decode( $token );
}

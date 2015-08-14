<?php
/**
 * Exception.php - Steelcode base exception class
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
 * Class Steelcode_Exception
 *
 * @category Steelcode
 * @package Steelcode_Exception
 */
class Steelcode_Exception extends Exception {

	/**
	 * Previous exception
	 *
	 * @var Exception
	 */
	private $_previous = null;

	/**
	 * Returns previous Exception
	 *
	 * @return Exception|null
	 */
	protected function _getPrevious() {
		return $this->_previous;
	}

	/**
	 * Class constructor
	 *
	 * @param string $message
	 * @param int $code
	 * @param Exception $previous
	 */
	public function __construct( $message = '', $code = 0, $previous = null ) {
		if (version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
			parent::__construct( $message, ( int ) $code );
			$this->_previous = $previous;
		} else {
			parent::__construct( $message, (int) $code, $previous );
		}
	}

	/**
	 * Provides access to the getPrevious() method for lower versions.
	 *
	 * @param string $method
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function __call( $method, array $args ) {
		if ( 'getprevious' == strtolower( $method ) ) {
			return $this->_getPrevious();
		}
		return null;
	}

	/**
	 * String representation of the exception
	 *
	 * @return string
	 */
	public function __toString() {
		if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
			$prevEx = $this->getPrevious();
			if ( null !== $prevEx ) {
				return $prevEx->__toString() . "\n\nNext " . parent::__toString();
			}
		}
		return parent::__toString();
	}
}

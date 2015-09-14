<?php
/**
 * Headers.php - Work with HTTP response headers
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
 * Class Steelcode_Http_Response_Headers
 *
 * @category Steelcode
 * @package Steelcode_Http
 */
class Steelcode_Http_Response_Headers {

	/**
	 * List of HTTP status codes and messages
	 *
	 * @var array
	 */
	private $_messages = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
		);

	/**
	 * Generated headers
	 *
	 * @var array
	 */
	private $_header;

	/**
	 * Create status header
	 *
	 * @param int $status
	 * @return string
	 */
	private function _statusHeader( $status ) {
		return sprintf(
			"%s %d %s",
			Steelcode_Server_Vars::protocol(),
			$status,
			$this->_messages[$status]
			);
	}

	/**
	 * Class constructor
	 *
	 * @param array $header
	 */
	public function __construct( $headers = null ) {
		$this->_header = ( $headers === null || !is_array( $headers ) ) ?
			array (
				'status' => $this->_statusHeader( 200 ),
				'fields' => array()
				) : $headers;
	}

	/**
	 * Set fields in HTTP response
	 *
	 * @param string $field
	 * @param string $value
	 */
	public function setFields( $field, $value ) {
		if ( !isset( $this->_header['fields'] ) ) {
			$this->_header['fields'] = array();
		}

		$this->_header['fields'][Steelcode_String_Helper::replace( "-", "_", $field )] = $value;
	}

	/**
	 * Set the content type
	 *
	 * @param string $type
	 */
	public function setContentType( $type ) {
		$this->setFields( 'Content-Type', $type );
	}

	/**
	 * Set HTTP response status
	 * $status must be a valid HTTP status code
	 *
	 * @param int $status
	 * @throws Steelcode_Http_Exception
	 */
	public function setStatus( $status ) {
		if ( ! isset( $this->_messages[$status] ) ) {
			throw new Steelcode_Http_Exception( 'Invalid status message' );
		}

		$this->_header['status'] = $this->_statusHeader( $status );
	}

	/**
	 * Set a cookie
	 *
	 * @param string $name
	 * @param string $value
	 * @param bool $httpOnly
	 * @param string $expires
	 * @param string $path
	 * @param string $domain
	 */
	public function setCookie( $name, $value, $httpOnly=true, $expires='', $path='/', $domain='' ) {
		$expires = ( $expires === '' ) ?
			Steelcode_Date_Helper::UTCDate( '+7 day', 'D, j M Y H:i:s e' ) :
			Steelcode_Date_Helper::UTCDate( $expires, 'D, j M Y H:i:s e' );

		$cookie = "{$name}={$value};Path={$path};Expires={$expires}";

		$cookie .= ( Steelcode_String_Helper::isNull( $domain ) ? '' : ";Domain={$domain}" );
		$cookie .= ( Steelcode_Ssl_Helper::isSSL() ? ';Secure' : '' );
		$cookie .= ( $httpOnly ? ';HttpOnly' : '' );

		exit($cookie);
		//$this->setFields( 'Set-Cookie', $cookie );
	}

	/**
	 * Flush the headers
	 */
	public function flushHeaders() {
		foreach( $this->_header as $key => $value ) {
			if ( $key === 'fields' ) {
				foreach ( $this->_header['fields'] as $field => $value ) {
					header( Steelcode_String_Helper::replace( "_", "-", $field ) . ": " . $value );
				}

				continue;
			}

			header( $value );
		}
	}
}


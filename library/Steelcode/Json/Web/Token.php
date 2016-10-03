<?php
/**
 * Token.php - Implementation of JSON Web Tokens for passing claims
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
 * Class Json_Web_Token
 */
class Steelcode_Json_Web_Token extends Steelcode_Token_Abstract {

	/**
	 * Extra leeway time
	 *
	 * @var int
	 */
	private $_leeway = 0;

	/**
	 * Key for encoding
	 *
	 * @var string
	 */
	private $_key = '';

	/**
	 * Selected algorithm for signing token
	 *
	 * @var string
	 */
	private $_algorithm = 'HS256';

	/**
	 * List of available hash functions
	 *
	 * @var array
	 */
	protected $_methods = array(
		'HS256' => array( 'hash_hmac', 'SHA256' ),
		'HS512' => array( 'hash_hmac', 'SHA512' ),
		'HS384' => array( 'hash_hmac', 'SHA384' ),
		'RS256' => array( 'openssl', 'SHA256' ),
		);

	/**
	 * Reserved claims of JWT
	 *
	 * @var array
	 */
	private $_reservedClaims = array(
		'issuer'     => 'iss',
		'subject'    => 'sub',
		'audience'   => 'aud',
		'expiry'     => 'exp',
		'notbefore'  => 'nbf',
		'created'    => 'iat',
		'identifier' => 'jti'
		);

	/**
	 * JSON Web Token header
	 *
	 * @var array
	 */
	private $_header = array();

	/**
	 * JSON Web Token payload
	 *
	 * @var array
	 */
	private $_payload = array();

	/**
	 * JSON Web Token signature
	 *
	 * @var string
	 */
	private $_signature = '';

	/**
	 * Sign the token
	 *
	 * @param string $input
	 * @return mixed
	 */
	private function _sign( $input ) {
		list( $function, $algorithm ) = $this->_methods[$this->_algorithm];

		$signature = '';

		switch ( $function ) {
			case 'hash_hmac':
				$signature = hash_hmac( $algorithm, $input, $this->_key, true );
				break;

			case 'openssl':
				if ( ! openssl_sign( $input, $signature, $this->_key, $algorithm ) ) {
					throw new Steelcode_Token_Exception( 'OpenSSL could not sign the token' );
				}
				break;

			default:
				throw new Steelcode_Token_Exception( 'Unsupported algorithm specified for signing' );
				break;
		}

		return $signature;
	}

	/**
	 * Verify signature of a token
	 *
	 * @param string $message
	 * @param string $signature
	 *
	 * @return bool
	 */
	private function _verify( $message, $signature ) {
		list( $function, $algorithm ) = $this->_methods[$this->_algorithm];

		$verified = false;

		switch ( $function ) {
			case 'hash_hmac':
				$hash = hash_hmac( $algorithm, $message, $this->_key, true );

				$length = min( Steelcode_String_Helper::safeLength( $signature ),
					Steelcode_String_Helper::safeLength( $hash ) );

				$status = 0;

				for( $count = 0; $count < $length; $count++ ) {
					$status |= ( ord($signature[$count]) ^ ord( $hash[$count] ) );
				}

				$status |= Steelcode_String_Helper::safeLength( $signature ) ^ Steelcode_String_Helper::safeLength( $hash );
				$verified = ( $status === 0 );
				break;

			case 'openssl':
				$status = openssl_verify( $message, $signature, $this->_key, $this->_algorithm );

				if ( $status === false ) {
					$this->_setMessage( 'OpenSSL could not verify the signature' );
				}

				$verified = $signature;
				break;
		}

		return $verified;
	}

	/**
	 * Encode the JSON web token
	 */
	protected function _encode() {
		$this->_header['typ'] = 'JWT';
		$this->_header['alg'] = $this->_algorithm;

		$segments = array(
			'header'  => $this->urlSafeB64Encode( Steelcode_Json_Helper::encode( $this->_header ) ),
			'payload' => $this->urlSafeB64Encode( Steelcode_Json_Helper::encode( $this->_payload ) ),
			'signature' => ''
			);

		$this->_signature = $this->_sign( "{$segments['header']}.{$segments['payload']}" );

		$segments['signature'] = $this->urlSafeB64Encode( $this->_signature );

		return Steelcode_Array_Helper::implode( '.', $segments );
	}

	/**
	 * Decode the token
	 *
	 * @param string $token
	 * @return true
	 */
	protected function _decode( $token ) {
		$segments64 = Steelcode_String_Helper::explode( '.', $token );

		if ( count( $segments64 ) !== 3 ) {
			$this->_setMessage( 'Wrong number of segments. Token is not a valid JSON web token' );
			return false;
		}

		$segments = $segments64;

		foreach ( $segments as $index => $value ) {
			$segments[$index] = $this->urlSafeB64Decode( $value );

			if ( $index < 2 ) {
				$segments[$index] = Steelcode_Json_Helper::decode( $segments[$index] );
			}
		}

		if ( empty( $segments[0] ) ) {
			$this->_setMessage( 'Invalid header encoding' );
			return false;
		}

		if ( empty( $segments[1] ) ) {
			$this->_setMessage( 'Invalid claims encoding' );
			return false;
		}

		if ( empty( $segments[0]->alg ) || !isset( $this->_methods[$segments[0]->alg] ) ) {
			$this->_setMessage( 'Invalid or unsupported algorithm' );
			return false;
		}

		$this->_algorithm = $segments[0]->alg;

		if ( ! $this->_verify("{$segments64[0]}.{$segments64[1]}",
			$segments[2], $this->_key, $this->_algorithm ) ) {
			$this->_setMessage( 'Signature verification failed' );
			return false;
		}

		if ( isset( $segments[1]->nbf ) &&
			$segments[1]->nbf > ( Steelcode_Date_Helper::time() + $this->_leeway ) ) {
			$this->_setMessage( 'Cannot handle this token before ' .
				Steelcode_Date_Helper::date( DateTime::ISO8601, $segments[1]->nbf ) );

			return false;
		}

		if ( isset( $segments[1]->iat ) &&
			$segments[1]->iat > ( Steelcode_Date_Helper::time() + $this->_leeway ) ) {
			$this->_setMessage( 'Cannot handle token prior to ' .
				Steelcode_Date_Helper::date( DateTime::ISO8601, $segments[1]->iat ) );

			return false;
		}

		if ( isset( $segments[1]->exp ) &&
			( Steelcode_Date_Helper::time() - $this->_leeway) >= $segments[1]->exp ) {
			$this->_setMessage( 'Token expired' );
			return false;
		}

		$this->_header    = (array) $segments[0];
		$this->_payload   = (array) $segments[1];
		$this->_signature = $segments[2];

		return true;
	}

	/**
	 * Class constructor
	 *
	 * @param string $key
	 * @param string $algorithm
	 *
	 * @throws Steelcode_Token_Exception
	 */
	public function __construct( $key, $algorithm='HS256' ) {

		if ( !empty( $algorithm ) && ! Steelcode_Array_Helper::hasKey( $algorithm, $this->_methods ) ) {
			throw new Steelcode_Token_Exception( 'Unsupported algorithm' );

		} else {
			$this->_algorithm = $algorithm;
		}

		$this->_key = $key;
	}

	/**
	 * Set secret key for signing
	 *
	 * @param string $key
	 */
	public function setKey( $key ) {
		$this->_key = $key;
	}

	/**
	 * Set a new claim in payload
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function setClaim( $name, $value ) {
		$this->_payload[$name] = $value;
	}

	/**
	 * Set name of issuer of the token
	 *
	 * @param string $issuer
	 */
	public function setIssuer( $issuer ) {
		$this->setClaim( $this->_reservedClaims['issuer'], $issuer );
	}

	/**
	 * Set subject of the token
	 *
	 * @param string $subject
	 */
	public function setSubject( $subject ) {
		$this->setClaim( $this->_reservedClaims['subject'], $subject );
	}

	/**
	 * Set audience of the token
	 *
	 * @param string $audience
	 */
	public function setAudience( $audience ) {
		$this->setClaim( $this->_reservedClaims['audience'], $audience );
	}

	/**
	 * Set expiry date of the token
	 *
	 * @param string $date
	 */
	public function setExpiryDate( $date ) {
		$this->setClaim(
			$this->_reservedClaims['expiry'],
			Steelcode_Date_Helper::unixTime( $date )
		);
	}

	/**
	 * Set a unique identifier for the token
	 *
	 * @param string $identifier
	 */
	public function setIdentifier( $identifier ) {
		$this->setClaim( $this->_reservedClaims['identifier'], $identifier );
	}

	/**
	 * Set the time before which the token MUST NOT be accepted
	 *
	 * @param string $time
	 */
	public function setNotBeforeTime( $time ) {
		$this->setClaim(
			$this->_reservedClaims['notbefore'],
			Steelcode_Date_Helper::unixTime( $time )
		);
	}

	/**
	 * Get the payload
	 *
	 * @return array
	 */
	public function getPayload() {
		return $this->_payload;
	}
}

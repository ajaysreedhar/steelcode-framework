<?php
/**
 * Response.php - Create JSON responses according to jsonapi.org specification
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
 * Class Steelcode_Json_Response
 *
 * @category Steelcode
 * @package Steelcode_Json
 */
class Steelcode_Json_Api_Response {

	/**
	 * Top level members
	 *
	 * @var array
	 */
	private $_topLevels = array(
		'jsonapi'  => array( 'version' => '1.0' ),
		'data'     => array(),
		'errors'   => null,
		'meta'     => null,
		'links'    => null,
		'included' => null
		);

	/**
	 * Top level members status flags
	 *
	 * @var array
	 */
	private $_levelFlags = array(
		'jsonapi'  => true,
		'data'     => true,
		'errors'   => false,
		'meta'     => false,
		'links'    => false,
		'included' => false
		);

	/**
	 * Change level flags
	 *
	 * @param string $list
	 * @param bool $state
	 */
	private function _changeLevelFlags( $list, $state ) {
		$flags = Steelcode_String_Helper::explode( ',', $list );

		foreach ( $flags as $flag ) {
			$this->_levelFlags[trim($flag)] = $state;
		}
	}

	/**
	 * Class constructor
	 */
	public function __construct() {
	}

	/**
	 * Enable top level members
	 *
	 * @param string $list : list of members to be enabled separated by commas
	 */
	public function enableLevels( $list ) {
		$this->_changeLevelFlags( $list, true );
	}

	/**
	 * Disable top level members
	 *
	 * @param string $list : list of members to be disabled separated by commas
	 */
	public function disableLevels( $list ) {
		$this->_changeLevelFlags( $list, false );
	}

	/**
	 * Add data in the top level and disable error messages
	 *
	 * @param array $data
	 */
	public function setData( array $data ) {
		$this->_topLevels['data'] = $data;
		$this->enableLevels( 'data, included' );
		$this->disableLevels( 'errors' );
	}

	public function appendData( array $data ) {
		$this->_topLevels['data'][] = $data;
		$this->enableLevels( 'data, included' );
		$this->disableLevels( 'errors' );
	}

	/**
	 * Set an error information in the top level and disable data
	 *
	 * @param array $errors
	 */
	public function setErrorInfo( array $errors ) {
		$this->_topLevels['errors'] = $errors;
		$this->enableLevels( 'errors' );
		$this->disableLevels( 'data,included' );
	}

	/**
	 * Create data to be added in top level
	 *
	 * @param string $id
	 * @param string $type
	 * @param array $attributes
	 * @param array $meta
	 */
	public function createData( $id, $type, $attributes=null, $meta=null ) {
		$data = array(
			'id'         => $id,
			'type'       => $type
			);

		if ( !empty( $attributes ) )
			$data['attributes'] = $attributes;

		if ( !empty( $meta ) )
			$data['meta'] = $meta;

		$this->_topLevels['data'][] = $data;
		$this->enableLevels( 'data, included' );
		$this->disableLevels( 'errors' );
	}

	/**
	 * Create error information to be set in top level
	 *
	 * @param string $id
	 * @param string $status
	 * @param string $code
	 * @param string $title
	 * @param string $detail
	 */
	public function createErrorInfo( $id, $status, $code, $title, $detail=null ) {
		$errorInfo = array(
			'id'     => $id,
			'status' => $status,
			'code'   => $code,
			'title'  => $title
			);

		if ( !empty( $detail ) )
			$errorInfo['detail'] = $detail;

		$this->setErrorInfo( $errorInfo );
	}

	/**
	 * Flush the output
	 */
	public function flushOutput() {
		foreach ( $this->_levelFlags as $level => $state ) {
			if ( $state === false || empty( $this->_topLevels[$level] ) ) {
				unset ( $this->_topLevels[$level] );
			}
		}

		echo Steelcode_Json_Helper::encode( $this->_topLevels );
	}
}

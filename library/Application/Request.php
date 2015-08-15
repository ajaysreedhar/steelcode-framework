<?php
/**
 * Request.php - Steelcode application request processor
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
 * Class Steelcode_Application_Request
 *
 * @category Steelcode
 * @package Steelcode_Application
 */
class Steelcode_Application_Request {

	/**
	 * Application configuration
	 *
	 * @var Steelcode_Application_Config
	 */
	private $_config;

	/**
	 * Sets URI attributes in the configuration
	 *
	 * @param array $list
	 */
	private function _setUriAttributes( $list ) {
		$index = 0;

		foreach ( $list as $key => $value ) {
			$this->_config->setUriAttr( $index, $value );
			$index++;
		}
	}

	/**
	 * Calls the defaults() method in the
	 * application configuration
	 */
	private function _defaults() {
		$this->_config->defaults();
	}

	/**
	 * Set page not found error
	 */
	private function _pageNotFound() {
		$this->_config->setDomain( 'error' );
		$this->_config->setController( 'page-not-found' );
	}

	/**
	 * Generate request path from $_SERVER variable
	 *
	 * @return string
	 */
	public function _generatePath() {
		if (isset($_SERVER['REDIRECT_URL']) &&
			isset($_SERVER['SCRIPT_NAME']) && !isset($_SERVER['FCGI_ROLE'])) {
			$requestUrl = $_SERVER['REDIRECT_URL'];
			$scriptName = $_SERVER['SCRIPT_NAME'];

		} elseif ( isset( $_SERVER['REQUEST_URI'] ) ) {
			list($requestUrl) = Steelcode_String_Helper::explode( '?', $_SERVER['REQUEST_URI'] );
			$scriptName = $_SERVER['SCRIPT_NAME'];

		}else {
			return '/';
		}

		if ($requestUrl == '' || $requestUrl == '/')
			return '/';

		$arrayUrl    = Steelcode_String_Helper::explode( '/', $requestUrl );
		$arrayScript = Steelcode_String_Helper::explode( '/', $scriptName );

		foreach ($arrayScript as $key => $value) {
			if ($arrayUrl[$key] == $value) {
				unset($arrayUrl[$key]);
			}
		}

		return '/' . Steelcode_Array_Helper::implode( '/', $arrayUrl );
	}

	/**
	 * Extract controller and domain paths from request
	 *
	 * @param string $requestPath
	 */
	private function _extractPath( $requestPath ) {
		$lastChar = substr( $requestPath, -1 );

		if ( $lastChar == '/' ) {
			$this->_config->setDomain( trim( $requestPath, '/' ) );
			$this->_config->setController( 'index' );

			return;
		}

		$arrayReq = Steelcode_String_Helper::explode( '/', $requestPath );

		if ( $arrayReq[0] == '' )
			unset( $arrayReq[0] );

		$arrayLen = count( $arrayReq );

		if ( $arrayLen === 1 ) {
			$this->_config->setDomain( 'index' );
			$this->_config->setController( $arrayReq[1] );

		} elseif ( $arrayLen > 1 ) {
			$this->_config->setDomain( $arrayReq[1] );
			$this->_config->setController( $arrayReq[2] );

			if ( $arrayLen > 2 ) {
				unset( $arrayReq[1], $arrayReq[2] );
				$this->_setUriAttributes( $arrayReq );
			}
		} else {
			$this->_config->defaults();
		}
	}

	/**
	 * Initialize objects
	 */
	public function __construct( $path ) {
		$this->_config = new Steelcode_Application_Config( $path );
	}

	/*
	 * Extract paths
	 */
	public function extract() {
		$requestPath = Steelcode_Server_Vars::getVar( 'PATH_INFO' );

		if ( $requestPath === null )
			$requestPath = $this->_generatePath();

		if ( $requestPath === '/' ) {
			$this->_defaults();
		} else {
			$this->_extractPath( $requestPath );
		}

		if ( ! Steelcode_File_Helper::exists( $this->_config->getControllerPath() ) ) {
			$this->_pageNotFound();
		}

		return $this->_config;
	}
}


<?php
/**
 * Bootstrap.php - Steelcode application bootstrap
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
 * Class Steelcode_Application_Bootstrap
 *
 * Abstract class must be extended by Bootstrap class
 * of steelcode applications
 *
 * @category Steelcode
 * @package Steelcode_Application
 */
abstract class Steelcode_Application_Bootstrap {

	/**
	 * Absolute path to the application
	 *
	 * @var string
	 */
	private $_path = "";

	/**
	 * Initialize objects
	 *
	 * @param string $path
	 */
	public function __construct( $path ) {
		$this->_path    = $path;
	}

	/**
	 * Bootstrap the application
	 *
	 * @param string $applicationPath
	 * @return Stelcode_Application_Config
	 */
	public function bootstrap( $options=array() ) {
		$request = new Steelcode_Application_Request( $this->_path );
		$config  = $request->extract();

		$this->initialize( $config );

		return $config;
	}

	/**
	 * Application specific bootstrapping
	 *
	 * @param Steelcode_Application_Config $config
	 */
	abstract public function initialize( Steelcode_Application_Config $config );
}


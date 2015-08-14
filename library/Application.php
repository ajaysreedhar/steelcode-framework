<?php
/**
 * Application.php - Core application loader
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
 * Class Steelcode_Application
 *
 * Core application loader
 * bootstraps the application and dispatches the controller
 *
 * @category Steelcode
 * @package Steelcode_Application
 */
class Steelcode_Application {

	/**
	 * Application options
	 *
	 * @var array
	 */
	private $_options = array();

	/**
	 * Bootstraps the application
	 *
	 * @var Steelcode_Application_Bootstrapper
	 */
	private $_bootstrapper;

	/**
	 * Dispatches controller
	 *
	 * @var Steelcode_Application_Dispatcher
	 */
	private $_dispatcher;

	/**
	 * Application configuration
	 *
	 * @var Steelcode_Application_Config
	 */
	private $_config;

	/**
	 * Initialize objects
	 *
	 * @param string $path
	 */
	public function __construct( $path ) {
		$this->_bootstrapper = new Steelcode_Application_Bootstrapper( $path );
		$this->_dispatcher   = new Steelcode_Application_Dispatcher();

		$this->_config = $this->_bootstrapper->bootstrap( $this->_options );
	}

	/**
	 * Run the application
	 */
	public function run() {
		$this->_dispatcher->dispatch( $this->_config );
	}

	/**
	 * Returns controller path
	 *
	 * @return string
	 */
	public function getControllerPath() {
		return $this->_config->getControllerPath();
	}
}


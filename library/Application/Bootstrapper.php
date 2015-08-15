<?php
/**
 * Bootstrapper.php - Loads the bootstrap classes
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
 * Class Steelcode_Application_Bootstrapper
 *
 * @category Steelcode
 * @package Steelcode_Application
 */
class Steelcode_Application_Bootstrapper {

	/**
	 * Bootstrap object
	 *
	 * @var Bootstrap
	 */
	private $_bootstrap;

	/**
	 * Initialize with application path
	 *
	 * @param string $path
	 */
	public function __construct( $path ) {
		$this->_bootstrap = new Bootstrap( $path );
	}

	/**
	 * Bootstrap the application
	 *
	 * Throws Steelcode_Application_Exception if Bootstrap class
	 * does not extend Steelcode_Application_Bootstrap_Abstract
	 *
	 * @param array $options
	 * @return Stelcode_Application_Config
	 * @throws Steelcode_Application_Exception
	 */
	public function bootstrap( $options = array() ) {
		if ( !( $this->_bootstrap instanceof Steelcode_Application_Bootstrap ) ) {
			throw new Steelcode_Application_Exception (
				'Class Bootstrap does not extend Steelcode_Application_Bootstrap_Abstract'
				);
		}

		return $this->_bootstrap->bootstrap( $options );
	}
}

<?php
/**
 * Dispatcher.php - Create object of specified controller and dispatch it
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
 * Class Steelcode_Application_Dispatcher
 *
 * @category Steelcode
 * @packege Steelcode_Application
 */
class Steelcode_Application_Dispatcher {

	/**
	 * Object of current controller
	 *
	 * @var Steelcode_Domain_Controller
	 */
	private $_controller;

	/**
	 * Check whether the controller
	 * extends Steelcode_Domain_Controller
	 *
	 * @return bool
	 */
	private function _checkInstance() {
		return ( $this->_controller instanceof Steelcode_Domain_Controller );
	}

	/**
	 * Dispatch the controller
	 *
	 * @param Steelcode_Application_Config $config
	 * @throws Steelcode_Application_Exception
	 */
	public function dispatch( Steelcode_Application_Config $config ) {
		$className =  $config->getControllerClass();

		$this->_controller = new $className( $config );

		if ( ! $this->_checkInstance() ) {
			throw new Steelcode_Application_Exception(
				$className . ' does not extend Steelcode Domain Controller'
				);
		}

		$this->_controller->runAction();
	}
}


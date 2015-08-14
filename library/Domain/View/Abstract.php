<?php
/**
 * Abstract.php - Manages views and template systems
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
 * Class Steelcode_Domain_View_Abstract
 *
 * @category Steelcode
 * @package Steelcode_Domain
 */
abstract class Steelcode_Domain_View_Abstract {

	/**
	 * Array containing names of variables set using magic method __set()
	 *
	 * @var array
	 */
	private $_registeredVariables = array();

	/**
	 * Application configuration
	 * 
	 * @var Steelcode_Application_Config
	 */
	protected $_config;

	/**
	 * Assign a variable to the view script.
	 *
	 * Ensure that the caller is not attempting to set a
	 * protected or private member
	 *
	 * @param string $variable
	 * @param mixed $value
	 *
	 * @throws Steelcode_View_Exception
	 */
	public function __set( $variable, $value ) {
		if ( '_' === substr( $variable, 0, 1) ) {
			throw new Steelcode_View_Exception( 'Setting private or protected variables is not allowed here' );
		}

		$this->$variable = $value;

		if ( !in_array( $variable, $this->_registeredVariables ) )
			array_push( $this->_registeredVariables, $variable );
	}

	/**
	 * Unset a variable set by external callers
	 *
	 * @param string $variable
	 * @throws Steelcode_View_Exception
	 */
	public function __unset( $variable ) {
		if ( '_' == substr( $variable, 0, 1) )
			throw new Steelcode_View_Exception( 'Trying to unset private or protected variables is not allowed' );

		if ( false === ( $varIndex = array_search( $variable, $this->_registeredVariables ) ) ) {
			throw new Steelcode_View_Exception( 'Trying to unset a variable which is not already set' );

		} else {
			unset( $this->_registeredVariables[$varIndex] );
			unset( $this->$variable );
		}
	}

	/**
	 * Class constructor
	 * @param Steelcode_Application_Config $config
	 */
	public function __construct( Steelcode_Application_Config $config ) {
		$this->_config = $config;
	}

	/**
	 * Render the view
	 * Calls renderView method implemented in template class
	 */
	public function renderView() {
		$this->render();
	}

	/**
	 * Checks whether a variable is already registered
	 *
	 * @param string $variable
	 * @return bool
	 */
	public function hasVariable( $variable ) {
		if ( in_array( $variable, $this->_registeredVariables ) )
			return true;

		return false;
	}

	/**
	 * Checks whether a variable is already registered
	 *
	 * @param string $member
	 * @return bool
	 */
	public function hasMember( $member ) {
		return $this->hasVariable( $member );
	}

	/**
	 * Sets a member in a class
	 *
	 * @param string $member
	 * @param mixed $value
	 */
	public function setMember( $member, $value ) {
		$this->$member = $value;
	}

	/**
	 * Get a variable set my magic methods
	 *
	 * @param string $member
	 * @return mixed
	 */
	public function getMember( $member ) {
		if ( $this->hasVariable( $member ) ) {
			return $this->$member;
		}

		return null;
	}

	/**
	 * Get the application configuration
	 *
	 * @return Steelcode_Application_Config
	 */
	public function getConfig() {
		return $this->_config;
	}

	/**
	 * Render the view contents
	 * Must be implemented in template class
	 */
	abstract public function render();
}

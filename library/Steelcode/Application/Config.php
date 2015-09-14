<?php
/**
 * Config.php - Steelcode application configuration
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
 * Class Steelcode_Application_Config
 *
 * @category Steelcode
 * @package Steelcode_Application
 */
class Steelcode_Application_Config {

	/**
	 * Path to current application
	 *
	 * @var string
	 */
	private $_path = '';

	/**
	 * Current domain
	 *
	 * @var array
	 */
	private $_domain = array();

	/**
	 * Current controller
	 *
	 * @var array
	 */
	private $_controller = array();

	/**
	 * Application specific options
	 *
	 * @var array
	 */
	private $_options = array();

	/**
	 * Additional components of URI
	 *
	 * @var array
	 */
	private $_uri = array();

	/**
	 * Inaitialize members
	 * Sets application path
	 *
	 * @param string $path
	 */
	public function __construct( $path ) {
		$this->_path = $path;
		$this->defaults();
	}

	/**
	 * Set the default domain and controller
	 */
	public function defaults() {
		$this->setDomain( 'index' );
		$this->setController( 'index' );
	}

	/**
	 * Sets the domain name
	 *
	 * @param string $domain
	 */
	public function setDomain( $domain ) {
		$this->_domain['name'] = $domain;
	}

	/**
	 * Sets the controller name
	 * Finds the controller class name and file name
	 *
	 * @param string $controller
	 */
	public function setController( $controller ) {
		$class = ucfirst( Steelcode_String_Helper::dashedToCamel( $controller ) );

		$this->_controller['name']  = $controller;
		$this->_controller['class'] =  "Controller_{$class}";
		$this->_controller['file']  = "{$class}Controller.php";
	}

	/**
	 * Sets URI options
	 *
	 * @param array $uri
	 */
	public function setUri( array $uri ) {
		$this->_uri = $uri;
	}

	/**
	 * Set/add a new atribute to URI options
	 *
	 * @param string $attribute
	 * @param string $value
	 */
	public function setUriValue( $attribute, $value ) {
		$this->_uri[$attribute] = $value;
	}

	/**
	 * Set application specific options
	 *
	 * @param array $options
	 */
	public function setOptions( array $options=array() ) {
		$this->_options = $options;
	}

	/**
	 * Set a new application specific
	 * attribute-value pair
	 *
	 * @param string $attribute
	 * @param mixed $value
	 */
	public function setOptionValue( $attribute, $value ) {
		$this->_options[$attribute] = $value;
	}

	/**
	 * Get all options
	 *
	 * @return array
	 */
	public function getOptions() {
		return $this->_options;
	}

	/**
	 * Get value from options referenced by an attribute
	 *
	 * @param string $attribute
	 * @return mixed
	 */
	public function getOptionValue( $attribute ) {
		return $this->_options[$attribute];
	}

	/**
	 * Returns the path to current application
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->_path;
	}

	/**
	 * Get the current domain name
	 * as in the URL
	 *
	 * @return string
	 */
	public function getDomain() {
		return $this->_domain['name'];
	}

	/**
	 * Get the current controller name
	 * as in the URL
	 *
	 * @return string
	 */
	public function getControllerName() {
		return $this->_controller['name'];
	}

	/**
	 * Get the current controler
	 * @return mixed
	 */
	public function getControllerClass() {
		return $this->_controller['class'];
	}

	/**
	 * Get the absolute path to current controller
	 *
	 * @return string
	 */
	public function getControllerPath() {
		return "{$this->_path}controllers/{$this->_domain['name']}/{$this->_controller['file']}";
	}

	/**
	 * Get the URI options
	 *
	 * @return array
	 */
	public function getUri() {
		return $this->_uri;
	}

	/**
	 * Retrieve a specific URI option
	 *
	 * @param string $attribute
	 * @return string
	 */
	public function getUriValue( $attribute ) {
		return $this->_uri[$attribute];
	}
}


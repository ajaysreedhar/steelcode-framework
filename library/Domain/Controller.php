<?php
/**
 * Controller.php - Holds details about current controller and its view
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
 * Class Steelcode_Domain_Controller
 *
 * All controllers within the application should extend this class
 *
 * @category Steelcode
 * @package Steelcode_Domain
 */
abstract class Steelcode_Domain_Controller
	implements Steelcode_Domain_Controller_Interface {

	/**
	 * Application configuration
	 *
	 * @var Steelcode_Application_Dispatcher
	 */
	private $_config;

	/**
	 * Object of the current view
	 *
	 * @var Steelcode_Domain_View
	 */
	public $view;

	/**
	 * Initialize a view
	 */
	private function _initView() {
		$this->view = new Steelcode_Domain_View( $this->_config );
	}

	/**
	 * Initialize controller and view
	 *
	 * @param Steelcode_Application_Dispatcher $config
	 */
	public function __construct( Steelcode_Application_Config $config ) {
		$this->_config = $config;

		$this->_initView();
	}

	/**
	 * Run the controller actions and view action
	 */
	public function runAction() {
		$this->init();
		$this->controllerAction();

		$this->viewAction();
	}

	/**
	 * Initiate view action
	 */
	public function viewAction() {
		$this->view->renderView();
	}

	/**
	 * Redirect to another page and exit from current page
	 *
	 * @param string $url : new url
	 */
	public function redirect( $url ) {
		header( "Location: {$url}" );
		exit( 0 );
	}

	/**
	 * Redirect to another domain/controller
	 *
	 * @param string $domain
	 * @param string $controller
	 * @param array $args
	 */
	public function directTo( $domain='index', $controller='index', $args=null ) {
		$url = Steelcode_Application_Helper::buildUrl( $domain, $controller, $args );
		$this->redirect( $url );
	}

	/**
	 * Get url built from controller name and view name
	 *
	 * @param string $domain
	 * @param string $controller
	 * @param array $args
	 * 
	 * @return string
	 */
	public function url( $domain='index', $controller='index', $args=null ) {
		return Steelcode_Application_Helper::buildUrl( $domain, $controller, $args );
	}

	/**
	 * Method init must be defined in each controller
	 */
	abstract public function init();

	/**
	 * Method controllerAction must be defined in each controller
	 */
	abstract public function controllerAction();
}
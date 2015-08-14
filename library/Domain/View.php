<?php
/**
 * View.php - Steelcode Domain View
 * This class can be omitted if any template loaders are being used
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
 * Class Steelcode_Domain_View
 *
 * Steelcode view class includes <view>.phtml 
 * from application/view directory or as specified
 *
 * @category Steelcode
 * @package Steelcode_Domain
 */
class Steelcode_Domain_View extends Steelcode_Domain_View_Abstract {

	/**
	 * Path to the current view file
	 *
	 * @var string
	 */
	private $_viewPath;

	/**
	 * Path to the selected layout file
	 *
	 * @var string
	 */
	private $_layoutPath;

	/**
	 * Class constructor
	 *
	 * @param Steelcode_Application_Config $config
	 */
	public function __construct( Steelcode_Application_Config $config ) {
		parent::__construct( $config );

		$this->_viewPath = $config->getPath() . 'views/' . $config->getDomain() . '/'
			. $config->getControllerName() . '.phtml';

		$this->_layoutPath = $config->getPath() . 'layouts/layout.phtml';
	}

	/**
	 * View contents inside a layout file
	 */
	public function viewContents() {
		if ( null === $this->getViewPath() )
			return;

		/** @noinspection PhpUnusedLocalVariableInspection */
		$path = $this->getViewPath();

		$viewPath = ( file_exists( $this->getViewPath() ) ) ?
			$this->getViewPath() : CONSTANT( 'INCLUDE_PATH' ) . 'includes/path-not-found.php';

		/** @noinspection PhpIncludeInspection */
		require_once( $viewPath );
	}

	/**
	 * Render the view
	 */
	public function render() {
		if ( null === $this->getLayoutPath() )
			return;

		/** @noinspection PhpUnusedLocalVariableInspection */
		$path = $this->getLayoutPath();

		$layoutPath = ( Steelcode_File_Helper::exists( $this->getLayoutPath() ) ) ?
			$this->getLayoutPath() : CONSTANT( 'INCLUDE_PATH' ) . 'includes/path-not-found.php';

		/** @noinspection PhpIncludeInspection */
		require_once( $layoutPath );
	}

	/**
	 * Disable a previously set layout
	 */
	public function disableLayout() {
		$this->_layoutPath = $this->_viewPath;
		$this->_viewPath = null;
	}

	/**
	 * Disable a previously set view
	 */
	public function disableView() {
		$this->_layoutPath = null;
	}

	/**
	 * Set a custom layout
	 *
	 * @param string $layout
	 * @param array $config
	 */
	public function setLayout( $layout, $config = null ) {
		if ( $config === null || !is_array( $config ) ) {
			$this->_layoutPath = "{$this->_config->getPath()}layouts/layout-{$layout}.phtml";

		} else {
			$this->_layoutPath = "{$config['location']}/{$layout}.phtml";
		}
	}

	/**
	 * Set a custom view file
	 *
	 * @param string $view
	 * @param array $config
	 */
	public function setView( $view, $config = null ) {
		if ( $config === null || !is_array( $config ) ) {
			$this->_viewPath = $this->_config->getPath() . 'views/' .
				$this->_config->getDomain() . '/' . $view . '.phtml';

		} else {
			$this->_viewPath = $config['location'] . '/' . $view . '.phtml';
		}
	}

	/**
	 * Get the selected layout path
	 *
	 * @return string
	 */
	protected function getLayoutPath() {
		return $this->_layoutPath;
	}

	/**
	 * Get the current view path
	 *
	 * @return string
	 */
	protected function getViewPath() {
		return $this->_viewPath;
	}
}

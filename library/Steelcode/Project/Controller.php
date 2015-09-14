<?php
/**
 * Controller.php - Steelcode project controller
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
 * Class Steelcode_Project_Controller
 *
 * @category SteelCode
 * @package Steelcode_Project
 */
class Steelcode_Project_Controller extends Steelcode_Project_Component_Abstract {

	/**
	 * Name of domain
	 *
	 * @var string
	 */
	private $_domain;

	/**
	 * Name of controller
	 *
	 * @var string
	 */
	private $_controller;

	/**
	 * Name of view file
	 *
	 * @var string
	 */
	private $_view;

	/**
	 * View file source absolute path
	 *
	 * @var string
	 */
	private $_viewSource = null;

	/**
	 * Create the controller in the domain
	 */
	private function _createController() {
		$controller = ucfirst( Steelcode_String_Helper::dashedToCamel( $this->_name ) );
		$this->_controller = "{$controller}Controller.php";

		$this->_path = "{$this->_location}/application/controllers/{$this->_domain}/{$this->_controller}";

		$this->_filePointer = fopen( $this->_path, "w" );

		if ( $this->_filePointer === null ) {
			throw new Steelcode_Project_Exception( "* Error! Could not create controller:\n\t- {$this->_path}" );
		}

		fwrite( $this->_filePointer, "<?php\n" );

		$text = "/**\n * Class Controller_{$controller}\n */\n";
		fwrite( $this->_filePointer, $text );

		$text  = "class Controller_{$controller} extends Steelcode_Domain_Controller { \n\n";
		$text .= "\t/**\n\t * Initialize\n\t */\n";
		$text .= "\tpublic function init() {\n\n\t}\n\n";
		$text .= "\t/**\n\t * Controller action\n\t */\n";
		$text .= "\tpublic function controllerAction() {\n\n\t}\n}\n";

		fwrite( $this->_filePointer, $text );
	}

	/**
	 * Create the view file for the controller
	 */
	private function _createView() {
		$this->_view = Steelcode_String_Helper::camelToDashed( $this->_name ) . ".phtml";
		$this->_path = "{$this->_location}/application/views/{$this->_domain}/{$this->_view}";

		$this->_filePointer = fopen( $this->_path, "w" );

		if ( $this->_filePointer === null ) {
			throw new Steelcode_Project_Exception( "* Error! Could not create view:\n\t- {$this->_path}" );
		}

		if ( $this->_viewSource === null ) {
			fwrite($this->_filePointer, '<h3>This is the view file for ' .
				str_replace( '.phtml', '', $this->_view ) . '</h3>');

		} else {
			$stat = copy( $this->_viewSource, $this->_path );

			if ( !$stat )
				throw new Steelcode_Project_Exception( "* Error! Could not copy view:\n\t- {$this->_path}" );
		}
	}

	/**
	 * Creates controller and view
	 *
	 * @return bool
	 */
	protected function _createComponent() {
		try {
			$this->_createController();
			$this->_createView();

		} catch ( Steelcode_Project_Exception $ex ) {
			$this->_setErrorText( $ex->getMessage() );
			return false;
		}

		$this->_setMessageText( "Done! Successfully created new controller:\n\t- {$this->_name}" );
		return true;
	}

	/**
	 * Class constructor
	 *
	 * @param string $location
	 * @param string $domain
	 */
	public function __construct( $location=null, $domain=null ) {
		parent::__construct( $location );

		$this->_domain = $domain;
	}

	/**
	 * Set the domain
	 *
	 * @param string $domain
	 */
	public function setDomain( $domain ){
		$this->_domain = Steelcode_String_Helper::camelToDashed( $domain );
	}

	/**
	 * Set the view file source
	 *
	 * @param string $source
	 */
	public function setViewSource( $source ) {
		$this->_viewSource = $source;
	}

	/**
	 * Remove view source
	 */
	public function removeViewSource() {
		$this->_viewSource = null;
	}
}

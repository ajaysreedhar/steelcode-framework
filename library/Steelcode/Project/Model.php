<?php
/**
 * Model.php - Steelcode project model creation
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
 * Class Steelcode_Project_Model
 *
 * @category SteelCode
 * @package Steelcode_Project
 */
class Steelcode_Project_Model extends Steelcode_Project_Component_Abstract {

	/**
	 * Name of model
	 *
	 * @var string
	 */
	private $_model;

	/**
	 * Write model content
	 */
	private function _writeContent() {
		fwrite( $this->_filePointer, "<?php\n" );

		fwrite( $this->_filePointer,
			"/**\n * Class Model_{$this->_model}\n */\n" );

		$text  = "class Model_{$this->_model} " . '{' . "\n\n";
		$text .= "\t" . "public function __construct( " . '$args=null' . " ) {\n\n\t}\n}\n";

		fwrite( $this->_filePointer, $text );
	}

	/**
	 * Class constructor
	 *
	 * @param string $location
	 */
	public function __construct( $location=null ) {
		parent::__construct( $location );
	}

	/**
	 * Creates a new model
	 *
	 * @return bool
	 */
	protected function _createComponent() {
		$this->_model = ucfirst( Steelcode_String_Helper::dashedToCamel( $this->_name ) );
		$model = "{$this->_model}.php";

		$this->_path = "{$this->_location}/application/models/{$model}";

		$this->_filePointer = fopen( $this->_path, "w" );

		if ( $this->_filePointer === null ) {
			$this->_setErrorText( "Error! Could not create model:\n\t- {$this->_path}" );
			return false;
		}

		$this->_writeContent();

		$this->_setMessageText( "Done! Successfully created new model\n\t- {$this->_name}" );
		return true;
	}
}

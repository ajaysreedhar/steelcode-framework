<?php
/**
 * Bootstrap.php - Steelcode project bootstrap
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
 * Class Steelcode_Project_Bootstrap
 *
 * @category SteelCode
 * @package Steelcode_Project
 */
class Steelcode_Project_Bootstrap extends Steelcode_Project_Component_Abstract {

	/**
	 * Creates a new bootstrap class
	 *
	 * @return bool
	 */
	protected function _createComponent() {
		$bootstrap = 'Bootstrap.php';

		$this->_path = "{$this->_location}/application/{$bootstrap}";
		$this->_filePointer = fopen( $this->_path, "w" );

		if ( $this->_filePointer === null ) {
			$this->_setErrorText( "* Error! Could not create bootstrap:\n\t- {$this->_path}" );
			return false;
		}

		fwrite( $this->_filePointer, "<?php\n" );

		fwrite( $this->_filePointer,
			"/**\n * Class Bootstrap\n */\n" );

		fwrite( $this->_filePointer,
			"class Bootstrap extends Steelcode_Application_Bootstrap " . '{' . "\n\n" );

		$text = "\t/**\n\t * Class constructor\n\t */\n";
		$text .= "\t" . "public function __construct() {\n\n\t}\n\n";
		fwrite( $this->_filePointer, $text );

		$text = "\t/**\n\t * Run bootstrap\n\t */\n";
		$text .= "\t" . "public function runBootstrap() {\n\n\t}";
		fwrite( $this->_filePointer, $text );

		fwrite( $this->_filePointer, "\n}\n" );

		$this->_setMessageText( "Done! Successfully created new bootstrap file" );
		return true;
	}

	/**
	 * Class constructor
	 *
	 * @param string $location
	 */
	public function __construct( $location=null ) {
		parent::__construct( $location );
	}
}

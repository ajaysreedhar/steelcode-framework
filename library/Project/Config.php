<?php
/**
 * Config.php - Steelcode project config
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
 * Class Steelcode_Project_Config
 *
 * @category SteelCode
 * @package Steelcode_Project
 */
class Steelcode_Project_Config extends Steelcode_Project_Component_Abstract {

	/**
	 * Name of configuration
	 *
	 * @var string
	 */
	private $_config;

	/**
	 * Creates a new configuration class
	 *
	 * @return bool
	 */
	protected function _createComponent() {
		$this->_config = ucfirst( Steelcode_Utils_Helper::dashedToCamel( $this->_name ) );
		$config = "{$this->_config}.php";

		$this->_path = "{$this->_location}/application/configs/{$config}";

		$this->_filePointer = fopen( $this->_path, "w" );

		if ( $this->_filePointer === null ) {
			$this->_setErrorText( "* Error! Could not create configuration:\n\t- {$this->_path}" );
			return false;
		}

		fwrite( $this->_filePointer, "<?php\n" );

		fwrite( $this->_filePointer,
			"/**\n * Class Config_{$this->_config}\n */\n" );

		$text  = "class Config_{$this->_config} " . '{' . "\n\n}\n";
		fwrite( $this->_filePointer, $text );

		$this->_setMessageText( "Done! Successfully created new configuration class:\n\t- Config_{$this->_config}" );
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

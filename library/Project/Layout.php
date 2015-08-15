<?php
/**
 * Layout.php - Steelcode project layout creation
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
 * Class Steelcode_Project_Layout
 *
 * @category SteelCode
 * @package Steelcode_Project
 */
class Steelcode_Project_Layout extends Steelcode_Project_Component_Abstract {

	/**
	 * Creates a new layout file
	 *
	 * @return bool
	 */
	protected function _createComponent() {
		$layoutFile = "layout-" . strtolower( $this->_name ) . ".phtml";
		$this->_path = "{$this->_location}/application/layouts/{$layoutFile}";

		$this->_filePointer = fopen( $this->_path, "w" );

		$text = "<" . '?php $this->viewContents(); ?' . ">\n";

		fwrite( $this->_filePointer, $text );

		if ( $this->_filePointer == null ) {
			$this->_setErrorText( "* Error! Could not create layout:\n\t- {$this->_path}" );
			return false;
		}

		$this->_setMessageText( "Done! Successfully created new layout:\n\t- {$this->_name}" );
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

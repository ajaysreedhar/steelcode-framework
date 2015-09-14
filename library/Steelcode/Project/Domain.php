<?php
/**
 * Domain.php - Steelcode project domain
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
 * Class Steelcode_Project_Domain
 *
 * @category SteelCode
 * @package Steelcode_Project
 */
class Steelcode_Project_Domain extends Steelcode_Project_Component_Abstract {
    
    /**
     * Creates new domain
     *
     * @return bool
     */
    protected function _createComponent() {
        $objController = new Steelcode_Project_Controller( $this->_location );

        $paths = array(
            "{$this->_location}/application/controllers/{$this->_name}",
            "{$this->_location}/application/views/{$this->_name}"
        );

        foreach( $paths as $path ) {
            $this->_path = $path;

            if ( !mkdir( $this->_path ) ) {
                $this->_setErrorText( "Error! Could not create domain:\n\t- {$this->_path}" );
                return false;
            }
        }

        $objController->setDomain( $this->_name );
        $objController->create( 'index' );

        $this->_setMessageText( "Done! Successfully created new domain:\n\t- {$this->_name}" );
        return true;
    }

    /**
     * Class constructor
     *
     * Initialize _objController
     *
     * @param string $location
     */
    public function __construct( $location=null ) {
        parent::__construct( $location );
    }
}

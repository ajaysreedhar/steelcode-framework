<?php
/**
 * Application.php - Steelcode project application
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
 * Class Steelcode_Project_Application
 *
 * @category SteelCode
 * @package Steelcode_Project
 */
class Steelcode_Project_Application extends Steelcode_Project_Component_Abstract {

    /**
     * List of directories to be created
     *
     * @var array
     */
    private $_dirTree = array();

    /**
     * Creates a new application
     *
     * @return bool
     */
    protected function _createComponent() {
        $this->_path = "{$this->_location}/{$this->_name}";
        $file404     = dirname( ABSPATH ) . '/includes/404.php';
        $samples     = ABSPATH . 'example';

        if ( !mkdir( $this->_path ) ) {
            $this->_setErrorText( "* Error! Could not create application:\n\t- {$this->_name}" );
            return false;
        }

        if ( !copy( "{$samples}/index.php", "{$this->_path}/index.php" ) ) {
            $this->_setErrorText( "* Error! Could not create index file" );
            return false;
        }

        if ( !copy( "{$samples}/.htaccess", "{$this->_path}/.htaccess" ) ) {
            $this->_setErrorText( "* Error! Could not create .htaccess file" );
            return false;
        }

        foreach( $this->_dirTree as $directory ) {
            if ( !mkdir( "{$this->_path}/{$directory}" ) ) {
                $this->_setErrorText( "* Error! Could not create directory:\n\t- {$directory}" );
                return false;
            }

            if ( !copy( $file404, "{$this->_path}/{$directory}/index.php" ) ) {
                $this->_setErrorText( "* Error! Could not create file:\n\t- index.php" );
                return false;
            }
        }

        try {
            /* Create bootstrap file */
            $objBootstrap = new Steelcode_Project_Bootstrap( $this->_path );
            $objBootstrap->create( 'Bootstrap.php' );

            /* Write configuration object */
            $objConfig = new Steelcode_Project_Config( $this->_path );
            $objConfig->create( 'database' );

            /* Create index and page-not-found controllers */
            $objController = new Steelcode_Project_Controller( $this->_path );
            $objController->setDomain( 'error' );
            $objController->create( 'index' );
            $objController->setViewSource( $file404 );
            $objController->create( 'page-not-found' );

            $objController->removeViewSource();
            $objController->setDomain( 'index' );
            $objController->create( 'index' );

            /* Create default layout */
            $objLayouts = new Steelcode_Project_Layout( $this->_path );
            $objLayouts->create( 'layout' );

            /* Create sample model */
            $objModels = new Steelcode_Project_Model( $this->_path );
            $objModels->create( 'application' );

        } catch ( Steelcode_Project_Exception $ex ) {
            $this->_setErrorText( $ex->getMessage() );
            return false;
        }

        $this->_setMessageText( "Done! Successfully created new application:\n\t- {$this->_name}" );
        return true;
    }

    /**
     * Class constructor
     *
     * @param string $location
     */
    public function __construct( $location=null ) {

        $this->_dirTree = array(
            'application',
            'application/configs',
            'application/controllers',
            'application/controllers/error',
            'application/controllers/index',
            'application/layouts',
            'application/models',
            'application/views',
            'application/views/error',
            'application/views/index',
            'public'
            );

        parent::__construct( $location );
    }
}
